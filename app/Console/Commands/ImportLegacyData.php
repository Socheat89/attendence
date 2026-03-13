<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLegacyData extends Command
{
    protected $signature = 'db:import-legacy {file} {company_id=20}';
    protected $description = 'Import data from old SQL dump to a specific company ID';

    protected $idMaps = [
        'users' => [],
        'branches' => [],
        'employees' => [],
        'attendance_sessions' => [],
    ];

    public function handle()
    {
        $filePath = $this->argument('file');
        $companyId = $this->argument('company_id');

        if (!File::exists($filePath)) {
            $this->error("រកមិនឃើញ SQL File: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("--------------------------------------------------");
        $this->info("🚀 ចាប់ផ្តើមទាញយក Data ទៅក្រុមហ៊ុន ID: {$companyId}");
        $this->info("--------------------------------------------------");

        $this->ensureCompanyExists($companyId);

        $this->info("📖 កំពុងអាន File...");
        $sqlContent = File::get($filePath);

        DB::beginTransaction();
        try {
            $this->idMaps['users'] = $this->importTable($sqlContent, 'users', $companyId, [$this, 'mapUser']);
            $this->idMaps['branches'] = $this->importTable($sqlContent, 'branches', $companyId, [$this, 'mapBranch']);
            
            // ធានាថាមាន Branch យ៉ាងហោចណាស់មួយសម្រាប់ក្រុមហ៊ុននេះ
            if (empty($this->idMaps['branches'])) {
                $defaultBranchId = DB::table('branches')->insertGetId([
                    'company_id' => $companyId, 'name' => 'Default Branch', 'is_active' => 1
                ]);
                $this->defaultBranchId = $defaultBranchId;
            } else {
                $this->defaultBranchId = reset($this->idMaps['branches']);
            }

            $this->idMaps['employees'] = $this->importTable($sqlContent, 'employees', $companyId, [$this, 'mapEmployee']);
            $this->idMaps['attendance_sessions'] = $this->importTable($sqlContent, 'attendance_sessions', $companyId, [$this, 'mapSession']);
            
            $this->importTable($sqlContent, 'attendance_logs', $companyId, [$this, 'mapLog']);
            $this->importTable($sqlContent, 'schedules', $companyId, [$this, 'mapSchedule']);

            DB::commit();
            $this->info("--------------------------------------------------");
            $this->info("✅ ជោគជ័យ! ទិន្នន័យទាំងអស់ត្រូវបាន Merge រួចរាល់។");
            $this->info("--------------------------------------------------");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ បរាជ័យត្រង់: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function ensureCompanyExists($id)
    {
        if (!DB::table('companies')->where('id', $id)->exists()) {
            DB::table('companies')->insert([
                'id' => $id, 'name' => 'Imported Company ' . $id, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    private function importTable($sql, $table, $companyId, $callback)
    {
        $this->comment("👉 កំពុងបញ្ចូល {$table}...");
        $pattern = "/INSERT INTO `{$table}`.*VALUES\s*(.*);/isU";
        $mappings = [];

        if (preg_match_all($pattern, $sql, $matches)) {
            foreach ($matches[1] as $valuesBlock) {
                $rows = preg_split("/\),\s*\(/", trim($valuesBlock, "() "));
                foreach ($rows as $row) {
                    $data = str_getcsv($row, ",", "'");
                    $result = call_user_func($callback, $data, $companyId);
                    if ($result) {
                        $mappings[$this->clean($data[0])] = $result;
                    }
                }
            }
        }
        return $mappings;
    }

    private function clean($val) {
        $val = trim($val, "' ");
        return ($val === 'NULL' || $val === '') ? null : $val;
    }

    private function mapUser($row, $companyId) {
        if (count($row) < 5) return null;
        $email = $this->clean($row[2]);
        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            return DB::table('users')->insertGetId([
                'name' => $this->clean($row[1]), 'email' => $email, 'password' => $this->clean($row[8]),
                'company_id' => $companyId, 'is_active' => 1, 'created_at' => $this->clean($row[10]) ?? now(),
            ]);
        }
        DB::table('users')->where('id', $user->id)->update(['company_id' => $companyId]);
        return $user->id;
    }

    private function mapBranch($row, $companyId) {
        return DB::table('branches')->insertGetId([
            'company_id' => $companyId, 'name' => $this->clean($row[1]), 'address' => $this->clean($row[2]),
            'latitude' => $this->clean($row[3]), 'longitude' => $this->clean($row[4]), 'is_active' => 1,
        ]);
    }

    private function mapEmployee($row, $companyId) {
        $oldUserId = $this->clean($row[1]);
        $oldBranchId = $this->clean($row[3]);
        if (!isset($this->idMaps['users'][$oldUserId])) return null;

        return DB::table('employees')->insertGetId([
            'company_id' => $companyId, 'user_id' => $this->idMaps['users'][$oldUserId],
            'employee_id' => $this->clean($row[2]),
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'position' => $this->clean($row[5]), 'base_salary' => $this->clean($row[7]) ?? 0,
            'join_date' => $this->clean($row[11]),
        ]);
    }

    private function mapSession($row, $companyId) {
        $oldEmpId = $this->clean($row[1]);
        if (!isset($this->idMaps['employees'][$oldEmpId])) return null;
        return DB::table('attendance_sessions')->insertGetId([
            'company_id' => $companyId, 'employee_id' => $this->idMaps['employees'][$oldEmpId],
            'branch_id' => $this->idMaps['branches'][$this->clean($row[2])] ?? $this->defaultBranchId,
            'attendance_date' => $this->clean($row[3]), 'work_minutes' => $this->clean($row[6]) ?? 0,
        ]);
    }

    private function mapLog($row, $companyId) {
        $oldSessId = $this->clean($row[1]);
        $oldEmpId = $this->clean($row[2]);
        if (!isset($this->idMaps['attendance_sessions'][$oldSessId])) return null;
        return DB::table('attendance_logs')->insert([
            'company_id' => $companyId, 'attendance_session_id' => $this->idMaps['attendance_sessions'][$oldSessId],
            'employee_id' => $this->idMaps['employees'][$oldEmpId], 'scan_type' => $this->clean($row[4]),
            'scanned_at' => $this->clean($row[5]),
        ]);
    }

    private function mapSchedule($row, $companyId) {
        $oldEmpId = $this->clean($row[2]);
        if (!isset($this->idMaps['employees'][$oldEmpId])) return null;
        return DB::table('schedules')->insert([
            'company_id' => $companyId, 'employee_id' => $this->idMaps['employees'][$oldEmpId],
            'branch_id' => $this->idMaps['branches'][$this->clean($row[1])] ?? $this->defaultBranchId,
            'day_of_week' => $this->clean($row[3]), 'morning_in' => $this->clean($row[4]),
            'evening_out' => $this->clean($row[7]),
        ]);
    }
}
