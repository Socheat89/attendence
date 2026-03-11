<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="relative bg-white rounded-[24px] border border-slate-200/60 shadow-sm p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-blue-600 font-bold text-xs uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Account Management
                </div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">User <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent italic">Profile.</span></h2>
                <p class="text-slate-500 font-medium">Update your personal details, secure your account, and manage workspace settings.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 font-medium lowercase">{{ Auth::user()->roles->pluck('name')->first() }} Account</p>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-200">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=transparent&color=3b82f6&size=128&bold=true" class="w-12 h-12">
                    </div>
                </div>
            </div>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="bg-blue-600 text-white px-6 py-4 rounded-2xl flex items-center gap-4 shadow-lg shadow-blue-200 animate-in fade-in slide-in-from-top-2">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <div class="text-sm font-bold">Successfully updated your profile configuration.</div>
                <button @click="show = false" class="ml-auto opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Navigation & Stats -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[24px] border border-slate-200/60 shadow-sm p-2 space-y-1">
                    <button @click="document.getElementById('personal-section').scrollIntoView({behavior: 'smooth', block: 'center'})" class="w-full flex items-center gap-3 p-4 rounded-xl bg-slate-50 text-blue-600 font-bold text-sm transition-all text-left">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-100">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                        Personal Information
                    </button>
                    <button @click="document.getElementById('security-section').scrollIntoView({behavior: 'smooth', block: 'center'})" class="w-full flex items-center gap-3 p-4 rounded-xl text-slate-600 hover:bg-slate-50 font-bold text-sm transition-all text-left">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                        Security Settings
                    </button>
                    <button @click="document.getElementById('danger-section').scrollIntoView({behavior: 'smooth', block: 'center'})" class="w-full flex items-center gap-3 p-4 rounded-xl text-red-500 hover:bg-red-50 font-bold text-sm transition-all text-left">
                        <div class="w-8 h-8 rounded-lg bg-red-100 text-red-500 flex items-center justify-center">
                            <i class="fa-solid fa-circle-exclamation text-xs"></i>
                        </div>
                        Danger Zone
                    </button>
                </div>

                <div class="bg-slate-900 rounded-[24px] p-6 text-white overflow-hidden relative group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/20 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
                    <div class="relative z-10 space-y-4">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Workspace Details</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/5">
                                <span class="text-xs text-slate-400 font-bold">Company</span>
                                <span class="text-xs font-bold">{{ $uiCompanySetting->company_name ?? 'HRM System' }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/5">
                                <span class="text-xs text-slate-400 font-bold">Account Type</span>
                                <span class="text-xs font-bold text-blue-400">{{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/5">
                                <span class="text-xs text-slate-400 font-bold">Member Since</span>
                                <span class="text-xs font-bold">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Sections -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Personal Profile -->
                <section id="personal-section" class="bg-white rounded-[24px] border border-slate-200/60 shadow-sm overflow-hidden animate-in fade-in duration-700">
                    <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-id-card text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Personal Information</h3>
                            <p class="text-xs text-slate-400 font-medium">Inform the system about who you are.</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="name" :value="__('Full Display Name')" />
                                    <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" />
                                </div>
                                <div class="space-y-2">
                                    <x-input-label for="email" :value="__('Work Email Address')" />
                                    <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required />
                                    <x-input-error :messages="$errors->get('email')" />
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <x-input-label for="phone" :value="__('Contact Phone Number')" />
                                    <x-text-input id="phone" name="phone" type="text" :value="old('phone', $user->phone)" placeholder="+855 00 000 000" />
                                    <x-input-error :messages="$errors->get('phone')" />
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end">
                                <x-primary-button class="rounded-xl px-8">
                                    <i class="fa-solid fa-check-circle mr-2 opacity-50"></i>
                                    {{ __('Save Information') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Security Settings -->
                <section id="security-section" class="bg-white rounded-[24px] border border-slate-200/60 shadow-sm overflow-hidden animate-in fade-in duration-700 delay-150">
                    <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Security & Credentials</h3>
                            <p class="text-xs text-slate-400 font-medium">Keep your account safe and updated.</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-2">
                                <x-input-label for="current_password" :value="__('Confirm Current Password')" />
                                <x-text-input id="current_password" name="current_password" type="password" placeholder="••••••••••••" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="password" :value="__('New Password')" />
                                    <x-text-input id="password" name="password" type="password" placeholder="Min. 8 characters" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" />
                                </div>
                                <div class="space-y-2">
                                    <x-input-label for="password_confirmation" :value="__('Verify New Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Retype your password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end">
                                <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 shadow-emerald-100 rounded-xl px-8">
                                    <i class="fa-solid fa-key mr-2 opacity-50"></i>
                                    {{ __('Update Credentials') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Deactivation Zone -->
                <section id="danger-section" class="bg-red-50/20 rounded-[24px] border border-red-100 overflow-hidden">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-white text-red-600 flex items-center justify-center shadow-sm border border-red-100 flex-shrink-0">
                            <i class="fa-solid fa-user-slash text-2xl"></i>
                        </div>
                        <div class="text-center md:text-left flex-grow">
                            <h3 class="text-lg font-bold text-red-900">Account Deactivation</h3>
                            <p class="text-sm text-red-600/70 font-medium mt-1">This action is permanent and will remove all your access and history from the portal.</p>
                        </div>
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-red-200 transition-all flex-shrink-0">
                            Deactivate
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Deletion Verification Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 md:p-12 space-y-6">
            @csrf
            @method('delete')

            <div class="text-center space-y-4">
                <div class="w-20 h-20 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-3xl mx-auto shadow-inner border border-red-100">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-900 leading-tight">Authorize Deletion?</h2>
                    <p class="text-slate-500 font-medium px-4">This action cannot be undone. All data will be purged.</p>
                </div>
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="{{ __('Enter your password to confirm') }}" class="text-center" />
                <x-text-input id="password" name="password" type="password" class="text-center border-red-200 focus:ring-red-500 focus:border-red-500" placeholder="••••••••••••" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="text-center" />
            </div>

            <div class="pt-4 flex flex-col sm:flex-row gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="flex-1 px-6 py-4 text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-2xl transition-all">
                    Cancel and Keep Account
                </button>
                <button type="submit" class="flex-1 px-6 py-4 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-red-200 transition-all">
                    Yes, Delete Permanently
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>


