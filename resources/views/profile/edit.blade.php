<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Premium Header -->
        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 border border-slate-800 shadow-2xl mb-12 group">
            <!-- Decorative background elements -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-blue-500/20 rounded-full blur-[80px] pointer-events-none group-hover:bg-blue-500/30 transition-all duration-1000"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-[80px] pointer-events-none group-hover:bg-indigo-500/30 transition-all duration-1000"></div>
            
            <div class="relative z-10 p-10 sm:p-14 flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="text-center md:text-left space-y-4">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span class="text-xs font-bold text-blue-200 tracking-widest uppercase">Workspace Center</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                        Personal <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Settings</span>
                    </h2>
                    <p class="text-slate-400 font-medium text-lg max-w-xl">
                        Manage your profile details, secure your account, and customize your workspace experience.
                    </p>
                </div>
                
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-blue-500 rounded-[2rem] blur-xl opacity-40 group-hover:opacity-60 transition-opacity duration-500"></div>
                    <div class="relative w-32 h-32 rounded-[2rem] bg-white p-1.5 shadow-2xl overflow-hidden transform group-hover:-translate-y-2 transition-transform duration-500">
                        <img src="{{ Auth::user()->photo_path ? asset('storage/'.$user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0f172a&color=fff&size=256' }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover rounded-[1.6rem]">
                    </div>
                </div>
            </div>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                 class="fixed bottom-10 right-10 z-50 bg-slate-900 border border-slate-700 text-white px-6 py-4 rounded-2xl flex items-center gap-4 shadow-2xl">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">✓</div>
                <div>
                    <p class="font-bold text-sm">Successfully Updated</p>
                    <p class="text-xs text-slate-400">Your preferences have been saved.</p>
                </div>
                <button @click="show = false" class="ml-4 text-slate-400 hover:text-white transition-colors">
                    ✕
                </button>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Side Navigation -->
            <div class="lg:w-1/3 xl:w-1/4">
                <div class="sticky top-12 space-y-8">
                    <nav class="flex flex-col space-y-3">
                        <button @click="document.getElementById('profile-info').scrollIntoView({behavior: 'smooth'})" class="w-full text-center px-6 py-4 rounded-2xl bg-slate-900 text-white shadow-xl shadow-slate-900/20 font-bold text-sm hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Profile Details
                        </button>
                        <button @click="document.getElementById('security').scrollIntoView({behavior: 'smooth'})" class="w-full text-center px-6 py-4 rounded-2xl bg-white border border-slate-100 text-slate-600 hover:bg-slate-50 font-bold text-sm hover:scale-[1.02] active:scale-[0.98] transition-all shadow-[0_4px_20px_rgb(0,0,0,0.02)]">
                            Security & Login
                        </button>
                        <button @click="document.getElementById('danger-zone').scrollIntoView({behavior: 'smooth'})" class="w-full text-center px-6 py-4 rounded-2xl bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 font-bold text-sm hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Termination Zone
                        </button>
                    </nav>

                    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-100 pb-4">Current Session</p>
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Organization</p>
                                <p class="text-sm font-black text-slate-900 mt-1">{{ $uiCompanySetting->company_name ?? 'HRM Portal' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">System Role</p>
                                <p class="text-sm font-black text-slate-900 mt-1">{{ Auth::user()->roles->pluck('name')->first() ?? 'Employee' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:w-2/3 xl:w-3/4 space-y-12 pb-20">
                
                <!-- Profile Block -->
                <div id="profile-info" class="scroll-mt-12 bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="p-10 sm:p-12">
                        <div class="mb-10 flex flex-col items-center sm:items-start text-center sm:text-left">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Identity & Profile</h3>
                            <p class="text-sm text-slate-500 mt-2 font-medium">Update your photo and personal details here.</p>
                        </div>
                        
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('PATCH')

                            <!-- Avatar Upload Section -->
                            <div class="flex flex-col sm:flex-row items-center sm:items-center justify-center sm:justify-start gap-8 pb-10 border-b border-slate-100">
                                <div class="relative group mx-auto sm:mx-0">
                                    <div class="w-28 h-28 rounded-full border border-slate-200 overflow-hidden shadow-sm">
                                        <img id="preview" src="{{ $user->photo_path ? asset('storage/'.$user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f8fafc&color=0f172a&size=200' }}" 
                                             class="w-full h-full object-cover" />
                                    </div>
                                    <label for="photo" class="absolute inset-0 flex items-center justify-center bg-slate-900/60 opacity-0 group-hover:opacity-100 rounded-full cursor-pointer transition-opacity backdrop-blur-sm">
                                        <span class="text-white text-xs font-bold tracking-wider uppercase">Upload</span>
                                    </label>
                                    <input id="photo" name="photo" type="file" accept="image/*" class="hidden" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])" />
                                </div>
                                <div class="text-center sm:text-left">
                                    <h4 class="text-base font-bold text-slate-900">Profile Photo</h4>
                                    <p class="text-sm text-slate-500 mt-1">We support high-res JPG or PNG files.</p>
                                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                                <div class="space-y-3">
                                    <x-input-label for="name" :value="__('Full Name')" class="text-xs font-bold text-slate-700 uppercase tracking-wide" />
                                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" 
                                           class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-2xl px-5 py-4 text-slate-900 font-bold transition-all shadow-sm" />
                                    <x-input-error :messages="$errors->get('name')" />
                                </div>

                                <div class="space-y-3">
                                    <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold text-slate-700 uppercase tracking-wide" />
                                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" 
                                           class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-2xl px-5 py-4 text-slate-900 font-bold transition-all shadow-sm" />
                                    <x-input-error :messages="$errors->get('email')" />
                                </div>

                                <div class="md:col-span-2 space-y-3">
                                    <x-input-label for="phone" :value="__('Phone Number')" class="text-xs font-bold text-slate-700 uppercase tracking-wide" />
                                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +855 12 345 678" 
                                           class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-2xl px-5 py-4 text-slate-900 font-bold transition-all shadow-sm" />
                                    <x-input-error :messages="$errors->get('phone')" />
                                </div>
                            </div>

                            <div class="pt-8 flex justify-center sm:justify-end">
                                <button type="submit" class="w-full sm:w-auto bg-slate-900 text-white px-10 py-4 rounded-2xl font-bold text-sm hover:bg-slate-800 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-slate-900/20">
                                    Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Block -->
                <div id="security" class="scroll-mt-12 bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="p-10 sm:p-12">
                        <div class="mb-10 text-center sm:text-left">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Access & Security</h3>
                            <p class="text-sm text-slate-500 mt-2 font-medium">Please enter your current password to change your credentials.</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-10">
                            @csrf
                            @method('PUT')

                            <div class="space-y-10">
                                <div class="space-y-3">
                                    <x-input-label for="current_password" :value="__('Current Password')" class="text-xs font-bold text-slate-700 uppercase tracking-wide" />
                                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" placeholder="••••••••" 
                                           class="w-full sm:w-1/2 bg-slate-50 border-transparent focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-2xl px-5 py-4 text-slate-900 font-bold transition-all shadow-sm" />
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-10 border-t border-slate-50">
                                    <div class="space-y-3">
                                        <x-input-label for="password" :value="__('New Password')" class="text-xs font-bold text-slate-700 uppercase tracking-wide" />
                                        <input id="password" name="password" type="password" autocomplete="new-password" placeholder="••••••••" 
                                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-2xl px-5 py-4 text-slate-900 font-bold transition-all shadow-sm" />
                                        <x-input-error :messages="$errors->updatePassword->get('password')" />
                                    </div>

                                    <div class="space-y-3">
                                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-xs font-bold text-slate-700 uppercase tracking-wide" />
                                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="••••••••" 
                                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-2xl px-5 py-4 text-slate-900 font-bold transition-all shadow-sm" />
                                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 flex justify-center sm:justify-end border-t border-slate-50">
                                <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold text-sm hover:bg-indigo-700 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-indigo-600/20">
                                    Update Security Credentials
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Termination Zone -->
                <div id="danger-zone" class="scroll-mt-12 bg-white rounded-[2.5rem] border border-red-100 shadow-[0_8px_30px_rgb(239,68,68,0.05)] overflow-hidden">
                    <div class="p-10 sm:p-12 relative overflow-hidden">
                        <!-- Red glow background overlay -->
                        <div class="absolute top-0 right-0 w-80 h-80 bg-red-100/50 rounded-full blur-[60px] pointer-events-none -mr-40 -mt-40"></div>

                        <div class="relative z-10">
                            <div class="mb-10 text-center sm:text-left">
                                <h3 class="text-3xl font-black text-slate-900 tracking-tight">Danger Zone</h3>
                                <p class="text-sm text-slate-500 mt-2 font-medium">Permanently delete your account and all associated data.</p>
                            </div>

                            <div class="bg-red-50/80 p-6 sm:p-8 rounded-[2rem] border border-red-100 mb-8 backdrop-blur-sm">
                                <p class="text-sm font-bold text-red-900 leading-relaxed">
                                    Once your account is deactivated, all of your associated resources and data will be permanently deleted. Before deciding, please download any data or information that you wish to retain.
                                </p>
                            </div>
                            
                            <div class="flex justify-center sm:justify-start">
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
                                        class="w-full sm:w-auto bg-white border-2 border-red-100 text-red-600 hover:border-red-600 px-10 py-4 rounded-2xl font-bold text-sm hover:bg-red-50 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                    Permanently Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deletion Verification Modal (Minimalist) -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 sm:p-12 space-y-10 relative overflow-hidden bg-white">
            @csrf
            @method('delete')
            
            <!-- Red blob decorative -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-48 bg-red-50 rounded-[100%] blur-[40px] pointer-events-none -mt-24"></div>

            <div class="relative z-10 space-y-4 text-center">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 block font-black border-4 border-white shadow-xl">
                    !
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                    Are you absolutely sure?
                </h2>
                <p class="text-slate-500 font-medium text-sm leading-relaxed max-w-sm mx-auto">
                    This action cannot be undone. This will permanently delete your account and remove your data from our servers. Please enter your password to confirm.
                </p>
            </div>

            <div class="relative z-10 space-y-3">
                <x-input-label for="password" value="{{ __('Confirm Password') }}" class="text-xs font-bold text-slate-700 uppercase tracking-wide text-center block" />
                <input id="password" name="password" type="password" 
                       class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 rounded-2xl px-5 py-4 text-center text-slate-900 font-bold transition-all text-xl" 
                       placeholder="{{ __('••••••••') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="text-center" />
            </div>

            <div class="relative z-10 flex flex-col-reverse sm:flex-row justify-center gap-4 pt-4">
                <button type="button" x-on:click="$dispatch('close')" class="w-full sm:w-auto px-10 py-4 bg-slate-100 text-sm font-bold text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors">
                    Cancel Action
                </button>
                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-red-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Delete Account Now
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
