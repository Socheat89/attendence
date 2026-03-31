<x-layouts.admin>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Company Settings</h2>
            <p class="text-sm text-slate-500 mt-1">Configure global preferences, localization, and features</p>
        </div>
    </div>

    <!-- Alert / Flash Messages via Alpine -->
    @if(session('success') || session('status'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-start justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ session('success') ?? session('status') }}</p>
            </div>
            <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-start justify-between">
            <div class="flex flex-col">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-bold">Please correct the errors below:</p>
                </div>
                <ul class="list-disc pl-9 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" @click="show = false" class="text-red-500 hover:text-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div x-data="{ activeTab: 'branding' }" class="flex flex-col md:flex-row gap-6 max-w-6xl">
        <!-- Sidebar Tabs -->
        <div class="w-full md:w-64 shrink-0">
            <nav class="flex flex-col space-y-2 relative">
                <button type="button" @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'bg-white shadow-sm border-blue-200 text-blue-700' : 'border-transparent text-slate-600 hover:bg-slate-100'" class="px-4 py-3 text-left rounded-xl text-sm font-semibold border transition-all flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Branding & Identity
                </button>
                <button type="button" @click="activeTab = 'localization'" :class="activeTab === 'localization' ? 'bg-white shadow-sm border-blue-200 text-blue-700' : 'border-transparent text-slate-600 hover:bg-slate-100'" class="px-4 py-3 text-left rounded-xl text-sm font-semibold border transition-all flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    Localization
                </button>
                <button type="button" @click="activeTab = 'financial'" :class="activeTab === 'financial' ? 'bg-white shadow-sm border-blue-200 text-blue-700' : 'border-transparent text-slate-600 hover:bg-slate-100'" class="px-4 py-3 text-left rounded-xl text-sm font-semibold border transition-all flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Financial & Payroll
                </button>
                <button type="button" @click="activeTab = 'integrations'" :class="activeTab === 'integrations' ? 'bg-white shadow-sm border-blue-200 text-blue-700' : 'border-transparent text-slate-600 hover:bg-slate-100'" class="px-4 py-3 text-left rounded-xl text-sm font-semibold border transition-all flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    Integrations
                </button>
                @if(auth()->user()->hasRole('Super Admin'))
                <button type="button" @click="activeTab = 'permissions'" :class="activeTab === 'permissions' ? 'bg-white shadow-sm border-blue-200 text-blue-700' : 'border-transparent text-slate-600 hover:bg-slate-100'" class="px-4 py-3 text-left rounded-xl text-sm font-semibold border transition-all flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                    HR Permissions
                </button>
                @endif
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[500px] flex flex-col">
                <form method="POST" action="{{ route('admin.settings.update') }}" class="flex flex-col flex-1">
                    @csrf 
                    @method('PUT')
                    
                    <div class="p-8 flex-1">
                        <!-- Branding Section -->
                        <div x-show="activeTab === 'branding'" x-transition.opacity.duration.300ms style="display: none;" :style="activeTab === 'branding' ? 'display: block;' : ''">
                            <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">Branding & Identity</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Company Name</label>
                                    <input type="text" name="company_name" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2.5" value="{{ old('company_name', $setting->company_name) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Primary Color</label>
                                    <div class="flex items-center space-x-3 bg-slate-50 p-3 rounded-lg border border-slate-200 w-max">
                                        <input type="color" name="primary_color" class="h-10 w-16 p-1 border-slate-300 rounded shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 cursor-pointer" value="{{ old('primary_color', $setting->primary_color) }}">
                                        <span class="text-sm font-bold text-slate-700 uppercase" x-data="{ color: '{{ old('primary_color', $setting->primary_color) }}' }">{{ old('primary_color', $setting->primary_color) }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2">This color is used for the primary buttons and navigation highlights.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Localization Section -->
                        <div x-show="activeTab === 'localization'" x-transition.opacity.duration.300ms style="display: none;">
                            <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">Localization</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">Currency Symbol</label>
                                    <input type="text" name="currency" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 font-medium" value="{{ old('currency', $setting->currency) }}" placeholder="e.g. USD, EUR, ៛">
                                    <p class="text-xs text-slate-500 mt-2">The symbol used across payrolls and invoices.</p>
                                </div>
                                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">Timezone</label>
                                    <input type="text" name="timezone" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 font-medium" value="{{ old('timezone', $setting->timezone) }}" placeholder="e.g. Asia/Phnom_Penh">
                                    <p class="text-xs text-slate-500 mt-2">Ensures attendance and logs align with your local time.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Financial & Payroll Section -->
                        <div x-show="activeTab === 'financial'" x-transition.opacity.duration.300ms style="display: none;">
                            <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">Financial Rules</h3>
                            
                            <div class="mb-6 bg-slate-50 border border-slate-200 p-5 rounded-xl flex items-center justify-between">
                                <div>
                                    <h4 class="text-base font-semibold text-slate-800">Payroll Module</h4>
                                    <p class="text-xs text-slate-500 mt-1">Make payroll functionality available to employees and managers.</p>
                                </div>
                                <label class="inline-flex relative items-center cursor-pointer">
                                    <input type="hidden" name="payroll_enabled" value="0">
                                    <input type="checkbox" name="payroll_enabled" value="1" class="sr-only peer" @checked(old('payroll_enabled', $setting->payroll_enabled))>
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Overtime Rate Per Hour</label>
                                <div class="relative w-full md:w-1/2">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 font-bold">$</span>
                                    </div>
                                    <input type="number" name="overtime_rate_per_hour" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-8 font-medium" value="{{ old('overtime_rate_per_hour', $setting->overtime_rate_per_hour) }}">
                                </div>
                            </div>

                            <div class="w-full mb-6 bg-orange-50/50 rounded-xl border border-orange-200 p-6">
                                <div class="flex items-center gap-3 mb-5 border-b border-orange-200 pb-3">
                                    <div class="bg-orange-100 p-2.5 rounded-lg text-orange-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-800">Late Policy</h4>
                                        <p class="text-sm text-slate-500">Configure how late arrivals impact employee payroll.</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                                        <div class="absolute left-0 top-0 w-1.5 h-full bg-blue-500"></div>
                                        <label class="block text-sm font-bold text-slate-700 mb-3 ml-2">Free Late Allowance</label>
                                        <div class="flex items-center ml-2">
                                            <input type="number" name="allowed_late_count" step="1" class="w-24 text-center border-slate-300 rounded-l-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg font-bold text-slate-700" value="{{ old('allowed_late_count', $setting->allowed_late_count) }}">
                                            <div class="bg-slate-100 text-slate-600 px-4 py-[11px] rounded-r-lg border-y border-r border-slate-300 font-semibold text-sm">Times / Month</div>
                                        </div>
                                    </div>

                                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                                        <div class="absolute left-0 top-0 w-1.5 h-full bg-red-500"></div>
                                        <label class="block text-sm font-bold text-slate-700 mb-3 ml-2">Penalty per Violation</label>
                                        <div class="flex items-center ml-2">
                                            <div class="bg-slate-100 text-slate-500 px-4 py-[11px] rounded-l-lg border-y border-l border-slate-300 font-bold">$</div>
                                            <input type="number" name="late_deduction_amount" step="0.01" class="w-32 border-slate-300 rounded-r-lg shadow-sm focus:border-red-500 focus:ring-red-500 text-lg font-bold text-red-600" value="{{ old('late_deduction_amount', $setting->late_deduction_amount) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden">
                                    <input type="number" name="late_deduction_per_minute" step="0.0001" value="{{ old('late_deduction_per_minute', $setting->late_deduction_per_minute ?? 0) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Integrations Section -->
                        <div x-show="activeTab === 'integrations'" x-transition.opacity.duration.300ms style="display: none;">
                            <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">Integrations</h3>
                            
                            <div class="mb-6 bg-slate-50 border border-slate-200 p-5 rounded-xl flex items-center justify-between">
                                <div>
                                    <h4 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06-.01.13-.02.26z"/></svg>
                                        Live Telegram Attendance
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-1">Send a notification to the Telegram group instantly when an employee scans attendance.</p>
                                </div>
                                <label class="inline-flex relative items-center cursor-pointer">
                                    <input type="hidden" name="telegram_scan_enabled" value="0">
                                    <input type="checkbox" name="telegram_scan_enabled" value="1" class="sr-only peer" @checked(old('telegram_scan_enabled', $setting->telegram_scan_enabled))>
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="border border-slate-200 rounded-2xl overflow-hidden mt-6">
                                <div class="bg-gradient-to-r from-slate-100 to-slate-50 p-6 flex flex-col md:flex-row md:items-center justify-between border-b border-slate-200 gap-4">
                                    <div>
                                        <h5 class="text-lg font-bold text-slate-800">Telegram Group Setup</h5>
                                        <p class="text-sm text-slate-500 mt-1 max-w-lg">Connect a Telegram group to receive attendance updates, alerts, and more automatically.</p>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg whitespace-nowrap">Global Bot Config</span>
                                </div>
                                
                                <div class="bg-white p-8 text-center">
                                    @if($setting->telegram_chat_id)
                                        <div class="mb-5 inline-flex items-center justify-center bg-emerald-50 text-emerald-700 px-6 py-3 rounded-xl border border-emerald-200">
                                            <div class="bg-emerald-500 rounded-full p-1 mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="font-bold text-base tracking-wide">Connected (Chat ID: {{ $setting->telegram_chat_id }})</span>
                                        </div>
                                        <p class="text-sm text-slate-500 mb-6 font-medium">Your Telegram group is currently synced. You can reconnect a different group below if needed.</p>
                                    @else
                                        <div class="mb-5 mx-auto inline-flex p-5 rounded-full bg-slate-50 border-2 border-slate-100 text-slate-400">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06-.01.13-.02.26z"/></svg>
                                        </div>
                                        <p class="text-base text-slate-600 mb-6 font-medium max-w-sm mx-auto leading-relaxed">No Telegram group connected yet. Click the button below to add our Bot to your group.</p>
                                    @endif

                                    <a href="https://t.me/mekong_cyberunit_bot?startgroup={{ $setting->telegram_connection_token }}" target="_blank" class="inline-flex items-center gap-3 bg-[#0088cc] hover:bg-[#0077b5] text-white text-base font-bold py-3.5 px-8 rounded-xl shadow-lg transition-transform hover:scale-[1.02] active:scale-95">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06-.01.13-.02.26z"/></svg>
                                        @if($setting->telegram_chat_id) Reconnect Group @else Connect Group @endif
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- HR Permissions Section -->
                        @if(auth()->user()->hasRole('Super Admin'))
                        <div x-show="activeTab === 'permissions'" x-transition.opacity.duration.300ms style="display: none;">
                            <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">HR & Admin Access Controls</h3>
                            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 mb-6 text-sm font-medium flex items-start gap-3">
                                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Select the specific modules that users assigned the "Admin / HR" role are allowed to view and manage. Super Admins will always have full access.
                            </div>
                            
                            @php
                                $hrPermissions = old('hr_permissions', $setting->hr_permissions ?? []);
                                $modules = [
                                    'employees' => 'Employees & Departments',
                                    'attendance' => 'Attendance & QR Codes',
                                    'requests' => 'Leave & Overtime Requests',
                                    'payrolls' => 'Payroll Management',
                                    'performance' => 'Performance & KPIs',
                                    'security' => 'Security & Activity Logs',
                                    'settings' => 'System Settings & Subscription'
                                ];
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($modules as $key => $label)
                                <label class="flex items-center space-x-3 bg-white border border-slate-200 hover:border-blue-400 p-4 rounded-xl shadow-sm transition-colors cursor-pointer group">
                                    <input type="checkbox" name="hr_permissions[]" value="{{ $key }}" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5 transition-colors" {{ in_array($key, is_array($hrPermissions) ? $hrPermissions : []) ? 'checked' : '' }}>
                                    <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 flex items-center justify-end">
                        <input type="hidden" name="current_plan_name" value="{{ old('current_plan_name', $setting->current_plan_name) }}">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 px-8 rounded-xl shadow-sm shadow-blue-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>


