<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import-legacy {file} {company_id=20}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from old SQL dump to a specific company ID in Multi-tenant system';

    /**
     * To map old IDs to new IDs to maintain relationships
     */
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

        // ១. បង្កើតក្រុមហ៊ុនបើសិនអត់ទាន់មាន
        $this->ensureCompanyExists($companyId);

        $this->info("📖 កំពុងអាន File (ប្រហែលជាចំណាយពេលបន្តិច)...");
        $sqlContent = File::get($filePath);

        // ២. ដំណើរការ Import ក្នុង Transaction ដើម្បីសុវត្ថិភាព
        DB::beginTransaction();
        try {
            $this->importUsers($sqlContent, $companyId);
            $this->importBranches($sqlContent, $companyId);
            $this->importEmployees($sqlContent, $companyId);
            $this->importAttendanceSessions($sqlContent, $companyId);
            $this->importAttendanceLogs($sqlContent, $companyId);
            $this->importSchedules($sqlContent, $companyId);

            DB::commit();
            $this->info("--------------------------------------------------");
            $this->info("✅ ជោគជ័យ! ទិន្នន័យទាំងអស់ត្រូវបាន Merge ចូលរួចរាល់។");
            $this->info("--------------------------------------------------");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ បរាជ័យ: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function ensureCompanyExists($id)
    {
        if (!DB::table('companies')->where('id', $id)->exists()) {
            DB::table('companies')->insert([
                'id' => $id,
                'name' => 'Imported Company ' . $id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function parseValues($sql, $table)
    {
        // ប្រើ Regex ដើម្បីទាញយក values ពី INSERT statements
        $pattern = "/INSERT INTO `{$table}`.*VALUES\s*(.*);/isU";
        if (preg_match_all($pattern, $sql, $matches)) {
            $allRows = [];
            foreach ($matches[1] as $valuesBlock) {
                // បំបែក row នីមួយៗចេញពីគ្នា
                $rows = preg_split("/\),\s*\(/", trim($valuesBlock, "() "));
                foreach ($rows as $row) {
                    $allRows[] = str_getcsv($row, ",", "'");
                }
            }
            return $allRows;
        }
        return [];
    }

    private function clean($val)
    {
        $val = trim($val, "' ");
        return ($val === 'NULL' || $val === '') ? null : $val;
    }

    private function importUsers($sql, $companyId)
    {
        $this->comment("👉 កំពុងបញ្ចូល Users...");
        $rows = $this->parseValues($sql, 'users');
        foreach ($rows as $row) {
            if (count($row) < 5) continue;
            $oldId = $this->clean($row[0]);
            $email = $this->clean($row[2]);

            // បើ User មានស្រាប់ Update តែ company_id ទៅ
            $user = DB::table('users')->where('email', $email)->first();
            if (!$user) {
                $newId = DB::table('users')->insertGetId([
                    'name' => $this->clean($row[1]),
                    'email' => $email,
                    'password' => $this->clean($row[8]),
                    'company_id' => $companyId,
                    'is_active' => 1,
                    'created_at' => $this->clean($row[10]) ?? now(),
                ]);
            } else {
                $newId = $user->id;
                DB::table('users')->where('id', $newId)->update(['company_id' => $companyId]);
            }
            $this->idMaps['users'][$oldId] = $newId;
        }
    }

    private function importBranches($sql, $companyId)
    {
        $this->comment("👉 កំពុងបញ្ចូល Branches...");
        foreach ($this->parseValues($sql, 'branches') as $row) {
            $oldId = $this->clean($row[0]);
            $newId = DB::table('branches')->insertGetId([
                'company_id' => $companyId,
                'name' => $this->clean($row[1]),
                'address' => $this->clean($row[2]),
                'latitude' => $this->clean($row[3]),
                'longitude' => $this->clean($row[4]),
                'is_active' => 1,
            ]);
            $this->idMaps['branches'][$oldId] = $newId;
        }
    }

    private function importEmployees($sql, $companyId)
    {
        $this->comment("👉 កំពុងបញ្ចូល Employees...");
        foreach ($this->parseValues($sql, 'employees') as $row) {
            $oldUserId = $this->clean($row[1]);
            $oldBranchId = $this->clean($row[3]);
            if (!isset($this->idMaps['users'][$oldUserId])) continue;

            $newId = DB::table('employees')->insertGetId([
                'company_id' => $companyId,
                'user_id' => $this->idMaps['users'][$oldUserId],
                'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? null,
                'employee_id' => $this->clean($row[2]),
                'position' => $this->clean($row[5]),
                'base_salary' => $this->clean($row[7]) ?? 0,
                'join_date' => $this->clean($row[11]),
            ]);
            $this->idMaps['employees'][$this->clean($row[0])] = $newId;
        }
    }

    private function importAttendanceSessions($sql, $companyId)
    {
        $this->comment("👉 កំពុងបញ្ចូល វត្តមាន (Sessions)...");
        foreach ($this->parseValues($sql, 'attendance_sessions') as $row) {
            $oldEmpId = $this->clean($row[1]);
            if (!isset($this->idMaps['employees'][$oldEmpId])) continue;

            $newId = DB::table('attendance_sessions')->insertGetId([
                'company_id' => $companyId,
                'employee_id' => $this->idMaps['employees'][$oldEmpId],
                'branch_id' => $this->idMaps['branches'][$this->clean($row[2])] ?? null,
                'attendance_date' => $this->clean($row[3]),
                'work_minutes' => $this->clean($row[6]) ?? 0,
            ]);
            $this->idMaps['attendance_sessions'][$this->clean($row[0])] = $newId;
        }
    }

    private function importAttendanceLogs($sql, $companyId)
    {
        $this->comment("👉 កំពុងបញ្ចូល Scan Logs...");
        foreach ($this->parseValues($sql, 'attendance_logs') as $row) {
            $oldSessId = $this->clean($row[1]);
            if (!isset($this->idMaps['attendance_sessions'][$oldSessId])) continue;

            DB::table('attendance_logs')->insert([
                'company_id' => $companyId,
                'attendance_session_id' => $this->idMaps['attendance_sessions'][$oldSessId],
                'employee_id' => $this->idMaps['employees'][$this->clean($row[2])],
                'scan_type' => $this->clean($row[4]),
                'scanned_at' => $this->clean($row[5]),
                'device_info' => $this->clean($row[9]),
            ]);
        }
    }

    private function importSchedules($sql, $companyId)
    {
        $this->comment("👉 កំពុងបញ្ចូល កាលវិភាគការងារ...");
        foreach ($this->parseValues($sql, 'schedules') as $row) {
            $oldEmpId = $this->clean($row[2]);
            if (!isset($this->idMaps['employees'][$oldEmpId])) continue;

            DB::table('schedules')->insert([
                'company_id' => $companyId,
                'employee_id' => $this->idMaps['employees'][$oldEmpId],
                'day_of_week' => $this->clean($row[3]),
                'morning_in' => $this->clean($row[4]),
                'evening_out' => $this->clean($row[7]),
            ]);
        }
    }
}
