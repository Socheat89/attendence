<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ImportLegacyData extends Command
{
    protected $signature = 'db:import-legacy {file} {company_id=20}';
    protected $description = 'V7: Most robust line-by-line migration with progress tracking';

    protected $maps = ['users' => [], 'branches' => [], 'roles' => [], 'employees' => [], 'sessions' => []];
    protected $counts = [];

    public function handle()
    {
        $file = $this->argument('file');
        $cid = (int)$this->argument('company_id');

        if (!File::exists($file)) {
            $this->error("រកមិនឃើញ File: $file");
            return 1;
        }

        $this->info("🚀 [V7] ចាប់ផ្តើម Import ទិន្នន័យ (Company ID: $cid)");
        
        // Ensure company exists
        DB::table('companies')->updateOrInsert(['id'=>$cid], ['name'=>'SR Cosmetic', 'status'=>'active']);

        $handle = fopen($file, "r");
        $currentTable = null;

        DB::beginTransaction();
        try {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '--') || str_starts_with($line, '/*')) continue;

                // Detect Table Name
                if (preg_match('/INSERT INTO [`]?([a-z0-9_]+)[`]?/i', $line, $m)) {
                    $currentTable = $m[1];
                    if (!isset($this->counts[$currentTable])) $this->counts[$currentTable] = 0;
                }

                // Detect Data Row (starts with '(' and ends with '),' or ');')
                if ($currentTable && preg_match('/^\((.*)\)[,;]$/i', $line, $m)) {
                    $rowStr = $m[1];
                    // Skip if it's the header row (contains column names instead of numbers/values)
                    if (str_contains($rowStr, '`id`') || str_contains($rowStr, '`name`')) continue;

                    $data = str_getcsv($rowStr, ",", "'");
                    if ($this->importRow($currentTable, $data, $cid)) {
                        $this->counts[$currentTable]++;
                    }
                }

                if (str_ends_with($line, ';')) {
                    if ($currentTable && ($this->counts[$currentTable] ?? 0) > 0) {
                        $this->info("   ✅ Table `{$currentTable}`: បញ្ចូលបាន " . $this->counts[$currentTable] . " ជួរ");
                    }
                    $currentTable = null;
                }
            }

            fclose($handle);
            DB::commit();
            $this->info("\n🎉 រួចរាល់! ការបញ្ជូនទិន្នន័យបញ្ចប់ដោយជោគជ័យ។");
        } catch (\Exception $e) {
            if ($handle) fclose($handle);
            DB::rollBack();
            $this->error("\n❌ កំហុស: " . $e->getMessage());
        }

        return 0;
    }

    private function clean($v) {
        if ($v === null) return null;
        $v = trim($v, "' ");
        return ($v === 'NULL' || $v === '') ? null : $v;
    }

    private function importRow($table, $data, $cid)
    {
        $oldId = $this->clean($data[0]);
        if (!is_numeric($oldId) && $table !== 'model_has_roles') return false;

        try {
            switch ($table) {
                case 'roles':
                    $name = $this->clean($data[1]);
                    $id = DB::table('roles')->where('name', $name)->value('id');
                    if (!$id) $id = DB::table('roles')->insertGetId(['name'=>$name, 'guard_name'=>'web']);
                    $this->maps['roles'][$oldId] = $id;
                    return true;

                case 'branches':
                    $id = DB::table('branches')->insertGetId([
                        'company_id' => $cid,
                        'name' => $this->clean($data[1]),
                        'address' => $this->clean($data[2]),
                        'latitude' => $this->clean($data[3]) ?? 0,
                        'longitude' => $this->clean($data[4]) ?? 0,
                        'is_active' => 1, 'created_at' => now(), 'updated_at' => now()
                    ]);
                    $this->maps['branches'][$oldId] = $id;
                    return true;

                case 'users':
                    $email = $this->clean($data[2]);
                    $user = DB::table('users')->where('email', $email)->first();
                    $userData = [
                        'name' => $this->clean($data[1]), 'email' => $email, 'password' => $this->clean($data[8]),
                        'company_id' => $cid, 'branch_id' => $this->maps['branches'][$this->clean($data[3])] ?? null,
                        'is_active' => 1, 'created_at' => now(), 'updated_at' => now()
                    ];
                    if ($user) {
                        DB::table('users')->where('id', $user->id)->update(['company_id'=>$cid]);
                        $id = $user->id;
                    } else {
                        $id = DB::table('users')->insertGetId($userData);
                    }
                    $this->maps['users'][$oldId] = $id;
                    return true;

                case 'model_has_roles':
                    $roleId = $this->maps['roles'][$this->clean($data[0])] ?? null;
                    $userId = $this->maps['users'][$this->clean($data[2])] ?? null;
                    if ($roleId && $userId) {
                        $pivot = ['role_id'=>$roleId, 'model_id'=>$userId, 'model_type'=>'App\Models\User'];
                        if (Schema::hasColumn('model_has_roles', 'company_id')) $pivot['company_id'] = $cid;
                        DB::table('model_has_roles')->updateOrInsert($pivot, $pivot);
                        return true;
                    }
                    return false;

                case 'employees':
                    $userId = $this->maps['users'][$this->clean($data[1])] ?? null;
                    if ($userId) {
                        $id = DB::table('employees')->insertGetId([
                            'company_id' => $cid, 'user_id' => $userId, 'employee_id' => $this->clean($data[2]),
                            'branch_id' => $this->maps['branches'][$this->clean($data[3])] ?? DB::table('branches')->where('company_id', $cid)->value('id'),
                            'position' => $this->clean($data[5]), 'base_salary' => $this->clean($data[7]) ?? 0,
                            'join_date' => $this->clean($data[11]) ?: now(), 'created_at' => now(), 'updated_at' => now()
                        ]);
                        $this->maps['employees'][$oldId] = $id;
                        return true;
                    }
                    return false;

                case 'attendance_sessions':
                    $empId = $this->maps['employees'][$this->clean($data[1])] ?? null;
                    if ($empId) {
                        $id = DB::table('attendance_sessions')->insertGetId([
                            'company_id' => $cid, 'employee_id' => $empId,
                            'branch_id' => $this->maps['branches'][$this->clean($data[2])] ?? null,
                            'attendance_date' => $this->clean($data[3]),
                            'late_minutes' => $this->clean($data[4]) ?? 0,
                            'early_leave_minutes' => $this->clean($data[5]) ?? 0,
                            'work_minutes' => $this->clean($data[6]) ?? 0,
                            'created_at' => $this->clean($data[9]) ?? now(), 'updated_at' => now()
                        ]);
                        $this->maps['sessions'][$oldId] = $id;
                        return true;
                    }
                    return false;

                case 'attendance_logs':
                    $sesId = $this->maps['sessions'][$this->clean($data[1])] ?? null;
                    $empId = $this->maps['employees'][$this->clean($data[2])] ?? null;
                    if ($sesId && $empId) {
                        DB::table('attendance_logs')->insert([
                            'company_id' => $cid, 'attendance_session_id' => $sesId, 'employee_id' => $empId,
                            'branch_id' => $this->maps['branches'][$this->clean($data[3])] ?? null,
                            'scan_type' => $this->clean($data[4]), 'scanned_at' => $this->clean($data[5]),
                            'latitude' => $this->clean($data[6]) ?? 0, 'longitude' => $this->clean($data[7]) ?? 0,
                            'distance_from_branch' => $this->clean($data[8] ?? 0),
                            'device_info' => $this->clean($data[9] ?? null),
                            'ip_address' => $this->clean($data[10] ?? null),
                            'created_at' => $this->clean($data[12]) ?? now(), 'updated_at' => now()
                        ]);
                        return true;
                    }
                    return false;
            }
        } catch (\Exception $e) { return false; }
        return false;
    }
}
