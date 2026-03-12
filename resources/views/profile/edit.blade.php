<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Profile Settings - {{ config('app.name', 'Mekong CyberUnit') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
        }
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar to look cleaner */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased selection:bg-blue-500 selection:text-white" x-data="{ pageLoading: true }" x-init="setTimeout(() => pageLoading = false, 500); window.addEventListener('load', () => pageLoading = false)">

    <!-- Page Loading Overlay -->
    <div x-show="pageLoading" 
         style="display: flex;"
         class="fixed inset-0 z-[100] bg-slate-50 items-center justify-center transition-opacity duration-300"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
        </div>
    </div>

    <!-- Success Notification -->
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-1rem]" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-1rem]"
             class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-full flex items-center gap-3 shadow-lg shadow-green-500/10">
            <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
            <span class="text-sm font-bold">Successfully Updated</span>
            <button @click="show = false" class="ml-2 text-green-600 hover:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-full p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%233b82f6\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]">
        
        <div class="max-w-4xl mx-auto">
            
            <!-- Top Bar / Back Button -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors font-bold text-sm bg-white px-5 py-2.5 rounded-full shadow-sm border border-slate-200 hover:border-blue-300">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Back to Dashboard
                </a>
            </div>

            <!-- Profile Header Card -->
            <div class="bg-white rounded-[2rem] p-8 sm:p-10 shadow-sm border border-slate-100 mb-8 flex flex-col sm:flex-row items-center sm:items-start gap-8 relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-purple-50 to-pink-50 rounded-full blur-2xl -ml-20 -mb-20 pointer-events-none"></div>
                
                <!-- Avatar -->
                <div class="relative z-10 shrink-0">
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg overflow-hidden relative">
                        <img id="header-preview" src="{{ $user->photo_path ? asset('storage/'.$user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=eff6ff&color=1e3a8a&size=256' }}" 
                             class="w-full h-full object-cover" alt="{{ $user->name }}" />
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="relative z-10 flex-1 text-center sm:text-left pt-2">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">{{ $user->name }}</h1>
                    <p class="text-slate-500 flex items-center justify-center sm:justify-start gap-2 font-medium text-sm">
                        <i class="fa-regular fa-envelope"></i> {{ $user->email }}
                    </p>
                    
                    <div class="mt-5 flex flex-wrap justify-center sm:justify-start gap-3">
                        <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-shield"></i> {{ Auth::user()->roles->pluck('name')->first() ?? 'Employee' }}
                        </span>
                        <span class="px-4 py-1.5 bg-slate-50 text-slate-600 text-xs font-bold rounded-full border border-slate-200 flex items-center gap-1.5">
                            <i class="fa-regular fa-building"></i> {{ $uiCompanySetting->company_name ?? 'Organization' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Navigation Sidebar (Inline) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-3 sticky top-8">
                        <nav class="flex flex-col gap-1">
                            <a href="#profile" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-blue-50 text-blue-700 font-bold text-sm transition-colors border border-blue-100">
                                <i class="fa-regular fa-user"></i> Personal Details
                            </a>
                            <a href="#security" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-bold text-sm transition-colors">
                                <i class="fa-solid fa-shield-halved"></i> Security & Login
                            </a>
                            <a href="#danger" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl text-red-600 hover:bg-red-50 hover:text-red-700 font-bold text-sm transition-colors mt-4 border border-transparent hover:border-red-100">
                                <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                            </a>
                        </nav>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-900 text-white hover:bg-slate-800 active:scale-[0.98] font-bold text-sm transition-all shadow-lg shadow-slate-900/20">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Forms Content Area -->
                <div class="lg:col-span-8 space-y-8 pb-10">
                    
                    <!-- Profile Information Edit -->
                    <div id="profile" class="scroll-mt-8 bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 sm:p-10">
                            <div class="mb-8">
                                <h2 class="text-2xl font-black text-slate-900">Personal Information</h2>
                                <p class="text-sm text-slate-500 mt-1 font-medium">Update your photo and basic profile details.</p>
                            </div>
                            
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <!-- Photo Upload -->
                                <div class="flex items-center gap-6 p-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                    <div class="relative group cursor-pointer w-16 h-16 shrink-0">
                                        <img id="form-preview" src="{{ $user->photo_path ? asset('storage/'.$user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=eff6ff&color=1e3a8a&size=200' }}" 
                                             class="w-full h-full object-cover rounded-full border border-slate-200 shadow-sm group-hover:opacity-60 transition-opacity" />
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fa-solid fa-camera text-slate-900"></i>
                                        </div>
                                        <input id="photo" name="photo" type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                               onchange="document.getElementById('form-preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('header-preview').src = window.URL.createObjectURL(this.files[0]);" />
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-slate-900">Profile Photo</h3>
                                        <p class="text-xs text-slate-500 mt-1">Click the image to upload. Recommended size: 256x256px.</p>
                                        <x-input-error :messages="$errors->get('photo')" class="mt-2 text-xs" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                                    <div class="space-y-2">
                                        <label for="name" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-regular fa-user text-sm"></i>
                                            </div>
                                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required 
                                                   class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-slate-900 text-sm font-bold transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="E.g. John Doe" />
                                        </div>
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>

                                    <div class="space-y-2">
                                        <label for="email" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Email Address</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-regular fa-envelope text-sm"></i>
                                            </div>
                                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required 
                                                   class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-slate-900 text-sm font-bold transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="john@example.com" />
                                        </div>
                                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                        <label for="phone" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Phone Number</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-phone text-sm"></i>
                                            </div>
                                            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" 
                                                   class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-slate-900 text-sm font-bold transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="+855 12 345 678" />
                                        </div>
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>
                                </div>

                                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-6 pt-6">
                                    <button type="submit" class="bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-95 transition-all">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Edit -->
                    <div id="security" class="scroll-mt-8 bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 sm:p-10">
                            <div class="mb-8">
                                <h2 class="text-2xl font-black text-slate-900">Security & Password</h2>
                                <p class="text-sm text-slate-500 mt-1 font-medium">Please use a strong password to protect your account.</p>
                            </div>

                            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                                @csrf
                                @method('PUT')

                                <div class="space-y-2">
                                    <label for="current_password" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Current Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="fa-solid fa-lock text-sm"></i>
                                        </div>
                                        <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                                               class="w-full md:w-1/2 pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-900 text-sm font-bold transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="••••••••" />
                                    </div>
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4">
                                    <div class="space-y-2">
                                        <label for="password" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">New Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-key text-sm"></i>
                                            </div>
                                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                                   class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-900 text-sm font-bold transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="••••••••" />
                                        </div>
                                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                                    </div>

                                    <div class="space-y-2">
                                        <label for="password_confirmation" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Confirm New Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-key text-sm"></i>
                                            </div>
                                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                                   class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-900 text-sm font-bold transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="••••••••" />
                                        </div>
                                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                                    </div>
                                </div>

                                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-6 pt-6">
                                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 active:scale-95 transition-all">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div id="danger" class="scroll-mt-8 bg-white rounded-[2rem] shadow-sm border border-red-100 overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-[80px] pointer-events-none -mr-32 -mt-32"></div>
                        
                        <div class="p-8 sm:p-10 relative z-10">
                            <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-slate-900">Danger Zone</h2>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Permanently delete your account and data.</p>
                                </div>
                            </div>

                            <div class="bg-red-50/80 p-5 rounded-2xl mb-8 border border-red-100/50">
                                <p class="text-sm text-red-800 font-bold leading-relaxed">
                                    Once your account is deleted, all of your associated resources and data will be permanently deleted. This action cannot be reversed, so please proceed with caution.
                                </p>
                            </div>
                            
                            <div class="flex">
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
                                        class="bg-white border-2 border-red-100 text-red-600 px-8 py-3.5 rounded-xl font-bold text-sm hover:border-red-600 hover:bg-red-50 active:scale-95 transition-all shadow-sm">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deletion Verification Modal Using x-modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 sm:p-10 bg-white relative overflow-hidden">
            @csrf
            @method('delete')
            
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-32 bg-red-50 rounded-[100%] blur-[30px] pointer-events-none -mt-16"></div>

            <div class="text-center relative z-10">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 font-black shadow-sm">
                    <i class="fa-solid fa-exclamation"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-900 mb-2">Are you sure?</h2>
                <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8">
                    This action cannot be undone. Please enter your password to confirm you would like to permanently delete your account.
                </p>
                
                <div class="space-y-2 text-left mb-8">
                    <label for="delete_password" class="block text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Confirm Password</label>
                    <div class="relative max-w-xs mx-auto">
                        <input id="delete_password" name="password" type="password" 
                               class="w-full py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 rounded-xl text-center text-slate-900 font-bold transition-all placeholder:font-medium placeholder:text-slate-400" 
                               placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="text-center mt-2" />
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-3 relative z-10">
                <button type="button" x-on:click="$dispatch('close')" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 border border-slate-200 text-sm font-bold text-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                    Cancel Action
                </button>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-red-600 text-white font-bold text-sm rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/20 active:scale-95 transition-all">
                    Confirm Deletion
                </button>
            </div>
        </form>
    </x-modal>
</body>
</html>
