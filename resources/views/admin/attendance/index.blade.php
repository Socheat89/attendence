<x-layouts.admin>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">គ្រប់គ្រងវត្តមាន (Attendance Management)</h2>
    </div>

    <!-- Summary Widgets -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $summary['total'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">ចំនួនស្កេនសរុប (Total Scans)</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-orange-500 mb-1">{{ $summary['late'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">យឺត (Late)</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-teal-500 mb-1">{{ $summary['overtime'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">ថែមម៉ោង (With Overtime)</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">កាលបរិច្ឆេទ (Date)</label>
                <input type="date" name="date" value="{{ $selectedDate }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">សាខា (Branch)</label>
                <select name="branch_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
                    <option value="">គ្រប់សាខា (All Branches)</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(request('branch_id')==$branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">បុគ្គលិក (Employee)</label>
                <select name="employee_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
                    <option value="">គ្រប់បុគ្គលិក (All Employees)</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(request('employee_id')==$employee->id)>
                            {{ $employee->employee_id }} - {{ $employee->user?->name ?? 'Unknown User' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 h-[38px]">
                <button class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">ចម្រោះទិន្នន័យ (Filter)</button>
                <a href="{{ route('admin.attendance.index') }}" class="flex-1 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors text-center flex items-center justify-center focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">កំណត់ឡើងវិញ (Reset)</a>
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200 mb-0">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ request()->fullUrlWithQuery(['tab'=>'all','page'=>1]) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab==='all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">ទិន្នន័យទាំងអស់ (All)</a>
            
            <a href="{{ request()->fullUrlWithQuery(['tab'=>'late','page'=>1]) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center {{ $activeTab==='late' ? 'border-orange-500 text-orange-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                យឺត (Late)
                @if($summary['late']>0)<span class="ml-2 bg-orange-100 text-orange-800 py-0.5 px-2.5 inset-y-0 rounded-full text-xs font-semibold">{{ $summary['late'] }}</span>@endif
            </a>
        </nav>
    </div>

    @if($activeTab !== 'rejected')
    <div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="py-3 px-4">បុគ្គលិក (Employee)</th>
                        <th class="py-3 px-4">សាខា (Branch)</th>
                        <th class="py-3 px-4">ស្កេន (Scan)</th>
                        <th class="py-3 px-4">កាលបរិច្ឆេទ/ម៉ោង (Date/Time)</th>
                        <th class="py-3 px-4">ស្ថានភាព (Status)</th>
                        <th class="py-3 px-4">ចម្ងាយ (Distance)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($attendanceLogs as $log)
                        @php
                        $schedule = $scheduleMap[$log->branch_id] ?? null;
                        $scanAt = \Carbon\Carbon::parse($log->scanned_at);

                        // Determine scan label (Check-In / Check-Out)
                        $scanLabel = in_array($log->scan_type, ['morning_in', 'lunch_in'], true)
                            ? 'ស្កេនចូល (In)'
                            : (in_array($log->scan_type, ['lunch_out', 'evening_out'], true) ? 'ស្កេនចេញ (Out)' : '-');

                        // Use attendance session data to accurately determine lateness
                        $isLate = false;
                        if ($log->attendanceSession && $log->attendanceSession->late_minutes > 0) {
                            if (in_array($log->scan_type, ['morning_in', 'lunch_in'])) {
                                $isLate = true;
                            }
                        }

                        if ($activeTab === 'late' && !$isLate) {
                            continue;
                        }


                        $locationLink = ($log->latitude !== null && $log->longitude !== null)
                            ? 'https://maps.google.com/?q=' . number_format((float) $log->latitude, 6, '.', '') . ',' . number_format((float) $log->longitude, 6, '.', '')
                            : null;

                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-800">{{ $log->employee->user?->name ?? 'Unknown User' }}</div>
                            <div class="text-xs text-slate-500">{{ $log->employee->employee_id }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $log->employee->branch?->name??'-' }}</td>
                        <td class="py-3 px-4 text-sm text-slate-700">{{ $scanLabel }}</td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $scanAt->format('Y-m-d H:i:s') }}</td>
                        <td class="py-3 px-4">
                            @if($isLate)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">🔴 យឺត (Late)</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">🔵 ល្អ (Good)</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $log->distance_from_branch ? round($log->distance_from_branch) . ' m' : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-slate-500">មិនមានទិន្នន័យ (No attendance data for selected filters.)</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($attendanceLogs, 'links'))
        <div class="p-4 border-t border-slate-200">
            {{ $attendanceLogs->links() }}
        </div>
        @endif
    </div>
    @endif



</x-layouts.admin>