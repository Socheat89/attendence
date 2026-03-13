<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ImportLegacyData extends Command
{
    protected $signature = 'db:import-legacy {file} {company_id=20}';
    protected $description = 'Import data from old SQL dump to a specific company ID';

    protected $idMaps = [
        'users' => [],
        'branches' => [],
        'departments' => [],
        'employees' => [],
        'roles' => [],
        'attendance_sessions' => [],
    ];

    protected $defaultBranchId = null;

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

        $this->info("📖 កំពុងអាន File (ប្រហែលជាចំណាយពេលបន្តិច)...");
        $sqlContent = File::get($filePath);

        DB::beginTransaction();
        try {
            // Step 1: Map Roles first
            $this->idMaps['roles'] = $this->mapRoles($sqlContent, $companyId);

            // Step 2: Branches
            $this->idMaps['branches'] = $this->importTable($sqlContent, 'branches', $companyId, [$this, 'mapBranch']);
            
            // Determine default branch
            if (empty($this->idMaps['branches'])) {
                $this->defaultBranchId = DB::table('branches')->where('company_id', $companyId)->value('id');
                if (!$this->defaultBranchId) {
                    $this->defaultBranchId = DB::table('branches')->insertGetId([
                        'company_id' => $companyId, 'name' => 'Default Branch', 'is_active' => 1,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            } else {
                $this->defaultBranchId = reset($this->idMaps['branches']);
            }

            // Step 3: Users
            $this->idMaps['users'] = $this->importTable($sqlContent, 'users', $companyId, [$this, 'mapUser']);
            
            // Step 4: Map User Roles
            $this->importTable($sqlContent, 'model_has_roles', $companyId, [$this, 'mapUserRole']);

            // Step 5: Departments
            $this->idMaps['departments'] = $this->importTable($sqlContent, 'departments', $companyId, [$this, 'mapDepartment']);

            // Step 6: Employees
            $this->idMaps['employees'] = $this->importTable($sqlContent, 'employees', $companyId, [$this, 'mapEmployee']);
            
            // Step 7: Attendance Sessions
            $this->idMaps['attendance_sessions'] = $this->importTable($sqlContent, 'attendance_sessions', $companyId, [$this, 'mapSession']);
            
            // Step 8: Attendance Logs (SCAN TIMES)
            $this->importTable($sqlContent, 'attendance_logs', $companyId, [$this, 'mapLog']);
            
            // Step 9: Rejection Logs (If any)
            $this->importTable($sqlContent, 'attendance_rejection_logs', $companyId, [$this, 'mapRejectionLog']);

            // Step 10: Schedules
            $this->importTable($sqlContent, 'schedules', $companyId, [$this, 'mapSchedule']);
            
            // Step 11: QR Tokens
            $this->importTable($sqlContent, 'attendance_qr_tokens', $companyId, [$this, 'mapQrToken']);

            DB::commit();
            $this->info("--------------------------------------------------");
            $this->info("✅ ជោគជ័យ! ទិន្នន័យត្រូវបានបូកបញ្ចូលរួចរាល់។");
            $this->info("--------------------------------------------------");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ បរាជ័យត្រង់: " . $e->getMessage());
            $this->error("Line: " . $e->getLine());
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

    private function parseValues($sql, $table)
    {
        // Try with and without backticks for robustness
        $pattern = "/INSERT INTO `?{$table}`?.*VALUES\s*(.*);/isU";
        $allRows = [];

        if (preg_match_all($pattern, $sql, $matches)) {
            foreach ($matches[1] as $valuesBlock) {
                // Better regex to split rows by ),( but not matching inside strings
                // For simplicity, we stick to the previous split or a better one
                $rows = preg_split("/\),\s*\(/", trim($valuesBlock, "() "));
                foreach ($rows as $row) {
                    $allRows[] = str_getcsv($row, ",", "'");
                }
            }
        }
        return $allRows;
    }

    private function importTable($sql, $table, $companyId, $callback)
    {
        $rows = $this->parseValues($sql, $table);
        $total = count($rows);
        $this->comment("👉 កំពុងបញ្ចូល {$table} ({$total} ជួរ)...");
        
        $mappings = [];
        $success = 0;

        foreach ($rows as $row) {
            try {
                $result = call_user_func($callback, $row, $companyId);
                if ($result) {
                    $mappings[$this->clean($row[0])] = $result;
                    $success++;
                }
            } catch (\Exception $e) {
                // Log specific uniqueness errors or others
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    // Skip duplicates silently or with a small note
                } else {
                    $this->warn("⚠️ Error in {$table}: " . substr($e->getMessage(), 0, 100));
                }
            }
        }
        
        if ($total > 0) {
            $this->info("   Done: {$success}/{$total} ជួរបានបញ្ចូល។");
        }
        return $mappings;
    }

    private function clean($val) {
        if ($val === null) return null;
        $val = trim($val, "' ");
        return ($val === 'NULL' || $val === '') ? null : $val;
    }

    private function mapRoles($sql, $companyId)
    {
        $rows = $this->parseValues($sql, 'roles');
        $this->comment("👉 កំពុង Map Roles (" . count($rows) . ")...");
        $map = [];
        foreach ($rows as $row) {
            $oldId = $this->clean($row[0]);
            $roleName = $this->clean($row[1]);
            
            $newRole = DB::table('roles')->where('name', $roleName)->first();
            if (!$newRole) {
                $newRoleId = DB::table('roles')->insertGetId([
                    'name' => $roleName, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()
                ]);
            } else {
                $newRoleId = $newRole->id;
            }
            $map[$oldId] = $newRoleId;
        }
        return $map;
    }

    private function mapUserRole($row, $companyId) {
        $oldRoleId = $this->clean($row[0]);
        $oldUserId = $this->clean($row[2]);

        if (!isset($this->idMaps['users'][$oldUserId]) || !isset($this->idMaps['roles'][$oldRoleId])) {
            return null;
        }

        $newUserId = $this->idMaps['users'][$oldUserId];
        $newRoleId = $this->idMaps['roles'][$oldRoleId];

        if ($oldRoleId == 1) {
            DB::table('users')->where('id', $newUserId)->update(['is_super_admin' => true]);
        }

        $data = ['role_id' => $newRoleId, 'model_id' => $newUserId, 'model_type' => 'App\Models\User'];
        if (Schema::hasColumn('model_has_roles', 'company_id')) {
            $data['company_id'] = $companyId;
        }

        $query = DB::table('model_has_roles')->where('role_id', $newRoleId)->where('model_id', $newUserId)->where('model_type', 'App\Models\User');
        if (isset($data['company_id'])) $query->where('company_id', $companyId);

        if (!$query->exists()) {
            DB::table('model_has_roles')->insert($data);
        }
        return true;
    }

    private function mapUser($row, $companyId) {
        if (count($row) < 3) return null;
        $oldBranchId = $this->clean($row[3]);
        $email = $this->clean($row[2]);
        
        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            return DB::table('users')->insertGetId([
                'name' => $this->clean($row[1]), 'email' => $email, 
                'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
                'phone' => $this->clean($row[4]), 'password' => $this->clean($row[8]),
                'company_id' => $companyId, 'is_active' => 1, 'is_super_admin' => false,
                'created_at' => $this->clean($row[10]) ?? now(), 'updated_at' => $this->clean($row[11]) ?? now(),
            ]);
        }
        DB::table('users')->where('id', $user->id)->update([
            'company_id' => $companyId,
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId
        ]);
        return $user->id;
    }

    private function mapBranch($row, $companyId) {
        return DB::table('branches')->insertGetId([
            'company_id' => $companyId, 'name' => $this->clean($row[1]), 'address' => $this->clean($row[2]),
            'latitude' => $this->clean($row[3]), 'longitude' => $this->clean($row[4]),
            'allowed_radius_meters' => $this->clean($row[5]) ?? 200, 'is_active' => 1,
            'created_at' => $this->clean($row[8]) ?? now(), 'updated_at' => $this->clean($row[9]) ?? now(),
        ]);
    }

    private function mapDepartment($row, $companyId) {
        $oldBranchId = $this->clean($row[1]);
        return DB::table('departments')->insertGetId([
            'company_id' => $companyId, 
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'name' => $this->clean($row[2]), 'is_active' => $this->clean($row[3]) ?? 1,
            'created_at' => $this->clean($row[4]) ?? now(), 'updated_at' => $this->clean($row[5]) ?? now(),
        ]);
    }

    private function mapEmployee($row, $companyId) {
        $oldUserId = $this->clean($row[1]);
        $oldBranchId = $this->clean($row[3]);
        $oldDeptId = $this->clean($row[4]);

        if (!isset($this->idMaps['users'][$oldUserId])) return null;

        return DB::table('employees')->insertGetId([
            'company_id' => $companyId, 'user_id' => $this->idMaps['users'][$oldUserId],
            'employee_id' => $this->clean($row[2]),
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'department_id' => $this->idMaps['departments'][$oldDeptId] ?? null,
            'position' => $this->clean($row[5]), 'base_salary' => $this->clean($row[7]) ?? 0,
            'join_date' => $this->clean($row[11]),
            'created_at' => $this->clean($row[13]) ?? now(), 'updated_at' => $this->clean($row[14]) ?? now(),
        ]);
    }

    private function mapSession($row, $companyId) {
        $oldEmpId = $this->clean($row[1]);
        $oldBranchId = $this->clean($row[2]);
        if (!isset($this->idMaps['employees'][$oldEmpId])) return null;
        
        return DB::table('attendance_sessions')->insertGetId([
            'company_id' => $companyId, 'employee_id' => $this->idMaps['employees'][$oldEmpId],
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'attendance_date' => $this->clean($row[3]), 
            'late_minutes' => $this->clean($row[4]) ?? 0,
            'early_leave_minutes' => $this->clean($row[5]) ?? 0,
            'work_minutes' => $this->clean($row[6]) ?? 0,
            'created_at' => $this->clean($row[9]) ?? now(), 'updated_at' => $this->clean($row[10]) ?? now(),
        ]);
    }

    private function mapLog($row, $companyId) {
        $oldSessId = $this->clean($row[1]);
        $oldEmpId = $this->clean($row[2]);
        $oldBranchId = $this->clean($row[3]);

        if (!isset($this->idMaps['attendance_sessions'][$oldSessId])) return null;
        if (!isset($this->idMaps['employees'][$oldEmpId])) return null;

        return DB::table('attendance_logs')->insert([
            'company_id' => $companyId, 
            'attendance_session_id' => $this->idMaps['attendance_sessions'][$oldSessId],
            'employee_id' => $this->idMaps['employees'][$oldEmpId], 
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'scan_type' => $this->clean($row[4]), 'scanned_at' => $this->clean($row[5]),
            'latitude' => $this->clean($row[6]) ?? 0, 'longitude' => $this->clean($row[7]) ?? 0,
            'distance_from_branch' => $this->clean($row[8]),
            'device_info' => $this->clean($row[9]), 'ip_address' => $this->clean($row[10]),
            'qr_token' => $this->clean($row[11]),
            'created_at' => $this->clean($row[12]) ?? now(), 'updated_at' => $this->clean($row[13]) ?? now(),
        ]);
    }

    private function mapRejectionLog($row, $companyId) {
        $oldEmpId = $this->clean($row[1]);
        $oldBranchId = $this->clean($row[2]);
        if (!isset($this->idMaps['employees'][$oldEmpId])) return null;

        return DB::table('attendance_rejection_logs')->insert([
            'company_id' => $companyId,
            'employee_id' => $this->idMaps['employees'][$oldEmpId],
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'scan_type' => $this->clean($row[3]),
            'rejection_reason' => $this->clean($row[8]),
            'device_info' => $this->clean($row[9]),
            'ip_address' => $this->clean($row[10]),
            'latitude' => $this->clean($row[5]) ?? 0,
            'longitude' => $this->clean($row[6]) ?? 0,
            'distance_from_branch' => $this->clean($row[7]),
            'created_at' => $this->clean($row[12]) ?? now(),
            'updated_at' => $this->clean($row[13]) ?? now(),
        ]);
    }

    private function mapSchedule($row, $companyId) {
        $oldEmpId = $this->clean($row[2]);
        $oldBranchId = $this->clean($row[1]);
        if (!isset($this->idMaps['employees'][$oldEmpId])) return null;
        
        return DB::table('schedules')->insert([
            'company_id' => $companyId, 'employee_id' => $this->idMaps['employees'][$oldEmpId],
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'day_of_week' => $this->clean($row[3]), 
            'morning_in' => $this->clean($row[4]), 'lunch_out' => $this->clean($row[5]),
            'lunch_in' => $this->clean($row[6]), 'evening_out' => $this->clean($row[7]),
            'created_at' => $this->clean($row[10]) ?? now(), 'updated_at' => $this->clean($row[11]) ?? now(),
        ]);
    }

    private function mapQrToken($row, $companyId) {
        $oldBranchId = $this->clean($row[1]);
        return DB::table('attendance_qr_tokens')->insert([
            'company_id' => $companyId,
            'branch_id' => $this->idMaps['branches'][$oldBranchId] ?? $this->defaultBranchId,
            'token_date' => $this->clean($row[2]), 'token' => $this->clean($row[3]),
            'expires_at' => $this->clean($row[4]), 'is_active' => $this->clean($row[5]) ?? 1,
            'created_at' => $this->clean($row[6]) ?? now(), 'updated_at' => $this->clean($row[7]) ?? now(),
        ]);
    }
}
