<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ImportLegacyData extends Command
{
    protected $signature = 'db:import-legacy {file} {company_id=20}';
    protected $description = 'Robust import with detailed debugging information';

    protected $idMaps = ['users' => [], 'branches' => [], 'departments' => [], 'employees' => [], 'roles' => [], 'attendance_sessions' => []];
    protected $defaultBranchId = null;

    public function handle()
    {
        $filePath = $this->argument('file');
        $companyId = $this->argument('company_id');

        if (!File::exists($filePath)) {
            $this->error("រកមិនឃើញ SQL File: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("🚀 ចាប់ផ្តើម Import: [Company ID: {$companyId}]");
        $sqlContent = File::get($filePath);
        $this->info("📂 ទំហំ File: " . round(strlen($sqlContent) / 1024) . " KB");

        DB::beginTransaction();
        try {
            // STEP 1: ROLES
            $this->idMaps['roles'] = $this->processTable($sqlContent, 'roles', $companyId, [$this, 'mapRole']);
            // STEP 2: BRANCHES
            $this->idMaps['branches'] = $this->processTable($sqlContent, 'branches', $companyId, [$this, 'mapBranch']);
            $this->defaultBranchId = !empty($this->idMaps['branches']) ? reset($this->idMaps['branches']) : 1;
            // STEP 3: USERS
            $this->idMaps['users'] = $this->processTable($sqlContent, 'users', $companyId, [$this, 'mapUser']);
            
            // Link Roles to Users
            $this->processTable($sqlContent, 'model_has_roles', $companyId, [$this, 'mapUserRole']);

            // STEP 5: DEPARTMENTS
            $this->idMaps['departments'] = $this->processTable($sqlContent, 'departments', $companyId, [$this, 'mapDepartment']);
            // STEP 6: EMPLOYEES
            $this->idMaps['employees'] = $this->processTable($sqlContent, 'employees', $companyId, [$this, 'mapEmployee']);
            // STEP 7: ATTENDANCE SESSIONS
            $this->idMaps['attendance_sessions'] = $this->processTable($sqlContent, 'attendance_sessions', $companyId, [$this, 'mapSession']);
            // STEP 8: ATTENDANCE LOGS (SCAN DATA)
            $this->processTable($sqlContent, 'attendance_logs', $companyId, [$this, 'mapLog']);

            DB::commit();
            $this->info("\n✅ រួចរាល់! សូមពិនិត្យ Database ។");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\n❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function processTable($sql, $table, $cid, $callback) {
        $this->comment("\n👉 កំពុងស្វែងរក Table: `{$table}`...");
        
        // Regex ថ្មី ដែលខ្លាំងជាងមុន (handle whitespace, backticks, and multiple inserts)
        $pattern = "/INSERT\s+INTO\s+[`]?{$table}[`]?.*VALUES\s*(.*);/isU";
        $mappings = [];
        $totalFound = 0;
        $successCount = 0;

        if (preg_match_all($pattern, $sql, $matches)) {
            foreach ($matches[1] as $valuesBlock) {
                $rows = $this->robustSplit($valuesBlock);
                $totalFound += count($rows);
                
                foreach ($rows as $row) {
                    try {
                        $res = call_user_func($callback, $row, $cid);
                        if ($res) {
                            $mappings[$this->clean($row[0])] = $res;
                            $successCount++;
                        }
                    } catch (\Exception $e) {}
                }
            }
        }

        if ($totalFound > 0) {
            $this->info("   🎯 រកឃើញ {$totalFound} ជួរ | បញ្ចូលបាន {$successCount} ជួរ");
        } else {
            $this->warn("   ⚠️ រកមិនឃើញទិន្នន័យ INSERT សម្រាប់ `{$table}` ទេ!");
        }

        return $mappings;
    }

    private function robustSplit($values) {
        $rows = []; $currentRow = ""; $inString = false; $escape = false; $level = 0;
        $len = strlen($values);
        for ($i = 0; $i < $len; $i++) {
            $char = $values[$i];
            if ($char === "'" && !$escape) $inString = !$inString;
            if ($char === "\\" && !$escape) $escape = true; else $escape = false;
            if (!$inString) {
                if ($char === "(") { $level++; if ($level === 1) { $currentRow = ""; continue; } }
                if ($char === ")") { $level--; if ($level === 0) { $rows[] = str_getcsv($currentRow, ",", "'"); continue; } }
            }
            if ($level > 0) $currentRow .= $char;
        }
        return array_filter($rows);
    }

    private function clean($val) { return trim($val, "' "); }

    // Mapping Functions
    private function mapRole($r, $cid) { 
        $name = $this->clean($r[1]);
        $role = DB::table('roles')->where('name', $name)->first();
        return $role ? $role->id : DB::table('roles')->insertGetId(['name' => $name, 'guard_name' => 'web']);
    }

    private function mapBranch($r, $cid) {
        return DB::table('branches')->insertGetId(['company_id' => $cid, 'name' => $this->clean($r[1]), 'is_active' => 1]);
    }

    private function mapUser($r, $cid) {
        $email = $this->clean($r[2]);
        $oldB = $this->clean($r[3]);
        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            return DB::table('users')->insertGetId([
                'name' => $this->clean($r[1]), 'email' => $email, 'password' => $this->clean($r[8]),
                'branch_id' => $this->idMaps['branches'][$oldB] ?? $this->defaultBranchId,
                'company_id' => $cid, 'is_active' => 1
            ]);
        }
        DB::table('users')->where('id', $user->id)->update(['company_id' => $cid]);
        return $user->id;
    }

    private function mapUserRole($r, $cid) {
        $oR = $this->clean($r[0]); $oU = $this->clean($r[2]);
        if (!isset($this->idMaps['users'][$oU], $this->idMaps['roles'][$oR])) return null;
        $data = ['role_id' => $this->idMaps['roles'][$oR], 'model_id' => $this->idMaps['users'][$oU], 'model_type' => 'App\Models\User'];
        if (Schema::hasColumn('model_has_roles', 'company_id')) $data['company_id'] = $cid;
        DB::table('model_has_roles')->updateOrInsert($data, $data);
        return true;
    }

    private function mapDepartment($r, $cid) {
        $oB = $this->clean($r[1]);
        return DB::table('departments')->insertGetId(['company_id' => $cid, 'branch_id' => $this->idMaps['branches'][$oB] ?? $this->defaultBranchId, 'name' => $this->clean($r[2]), 'is_active' => 1]);
    }

    private function mapEmployee($r, $cid) {
        $oU = $this->clean($r[1]); if (!isset($this->idMaps['users'][$oU])) return null;
        return DB::table('employees')->insertGetId(['company_id' => $cid, 'user_id' => $this->idMaps['users'][$oU], 'employee_id' => $this->clean($r[2]), 'branch_id' => $this->defaultBranchId, 'position' => $this->clean($r[5]), 'join_date' => $this->clean($r[11])]);
    }

    private function mapSession($r, $cid) {
        $oE = $this->clean($r[1]); if (!isset($this->idMaps['employees'][$oE])) return null;
        return DB::table('attendance_sessions')->insertGetId(['company_id' => $cid, 'employee_id' => $this->idMaps['employees'][$oE], 'branch_id' => $this->defaultBranchId, 'attendance_date' => $this->clean($r[3])]);
    }

    private function mapLog($r, $cid) {
        $oS = $this->clean($r[1]); $oE = $this->clean($r[2]);
        if (!isset($this->idMaps['attendance_sessions'][$oS], $this->idMaps['employees'][$oE])) return null;
        return DB::table('attendance_logs')->insert([
            'company_id' => $cid, 'attendance_session_id' => $this->idMaps['attendance_sessions'][$oS],
            'employee_id' => $this->idMaps['employees'][$oE], 'branch_id' => $this->defaultBranchId,
            'scan_type' => $this->clean($r[4]), 'scanned_at' => $this->clean($r[5]),
            'latitude' => $this->clean($r[6]) ?? 0, 'longitude' => $this->clean($r[7]) ?? 0,
            'created_at' => $this->clean($r[12]) ?? now()
        ]);
    }
}
