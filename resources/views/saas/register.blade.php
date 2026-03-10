<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Your Workspace | Mekong CyberUnit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .grad-text {
            background: linear-gradient(135deg, #60A5FA 0%, #818CF8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .animated-blob {
            filter: blur(80px);
            opacity: 0.6;
            animation: blob-float 20s infinite alternate;
        }
        @keyframes blob-float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 100px) scale(1.2); }
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 flex overflow-hidden">
    
    <!-- Left Visual Side -->
    <div class="hidden lg:flex lg:w-5/12 bg-[#0F172A] relative flex-col justify-between overflow-hidden">
        
        <!-- Rich Background -->
        <div class="absolute inset-0 z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] rounded-full bg-blue-600/20 animated-blob"></div>
            <div class="absolute bottom-[0%] -right-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/20 animated-blob" style="animation-delay: -5s;"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 32px 32px;"></div>
        </div>

        <div class="relative z-10 p-12">
            <a href="/" class="flex items-center gap-3 w-fit group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-xl shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-microchip text-white text-xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-2xl tracking-tight leading-none text-white">Mekong</span>
                    <span class="font-bold text-xs tracking-[0.3em] uppercase text-blue-400">CyberUnit</span>
                </div>
            </a>
        </div>

        <div class="relative z-10 p-12 pb-24">
            <div class="mb-6 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-widest shadow-inner">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Automated System Deployment
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight mb-8">
                Your enterprise <br><span class="grad-text">environment</span> is <br>ready to go.
            </h1>
            
            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-6 mb-12">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0 mt-1">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-1">Selected: {{ $plan->name }}</h4>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            @if($plan->price <= 0)
                                You get 7 days of full access to explore our platform features.
                            @else
                                You will get instant access to all premium HRM modules and dedicated support.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex -space-x-3">
                    <img src="https://ui-avatars.com/api/?name=JS&background=random" class="w-12 h-12 rounded-full border-4 border-[#0F172A] shadow-xl" alt="User">
                    <img src="https://ui-avatars.com/api/?name=AK&background=random" class="w-12 h-12 rounded-full border-4 border-[#0F172A] shadow-xl" alt="User">
                    <img src="https://ui-avatars.com/api/?name=ML&background=random" class="w-12 h-12 rounded-full border-4 border-[#0F172A] shadow-xl" alt="User">
                    <div class="w-12 h-12 rounded-full border-4 border-[#0F172A] bg-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-xl">1k+</div>
                </div>
                <div class="text-slate-400 font-medium text-xs leading-relaxed">
                    Trusted by <span class="text-white font-bold">1,000+ businesses</span><br>across Cambodia.
                </div>
            </div>
        </div>
    </div>

    <!-- Right Form Side -->
    <div class="flex-1 w-full lg:w-7/12 flex flex-col items-center justify-center relative overflow-y-auto bg-white lg:bg-slate-50 px-6 py-12 md:px-20">
        
        <!-- Mobile Header (Logo) -->
        <div class="lg:hidden absolute top-8 left-8 right-8 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg text-white">
                    <i class="fa-solid fa-microchip text-sm"></i>
                </div>
                <span class="font-extrabold text-lg text-slate-900 tracking-tight text-sm uppercase">Mekong</span>
            </a>
            <a href="{{ route('pricing') }}" class="text-xs font-bold text-blue-600">Back to Pricing</a>
        </div>

        <div class="w-full max-w-xl mx-auto py-10 lg:py-0">
            
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Set up your workspace</h2>
                <p class="text-slate-500 font-medium">Everything you need to launch your digitized HRM system.</p>
            </div>

            @if(session('error'))
            <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                </div>
                <div class="flex-1">
                    <h5 class="font-bold text-sm mb-0.5">Registration Issue</h5>
                    <p class="text-xs leading-relaxed">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <form action="{{ route('register.company.store', $plan->id) }}" method="POST" class="space-y-8">
                @csrf

                <!-- Section 1: Company -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold shadow-sm">1</div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Company Profile</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Company Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required autofocus
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all transition-all"
                                    placeholder="e.g. Mekong CyberUnit Co., Ltd">
                            </div>
                            @error('company_name')<span class="text-rose-500 text-[10px] mt-1 ml-1 block font-bold uppercase tracking-tight">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="company_email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Company Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <input type="email" name="company_email" id="company_email" value="{{ old('company_email') }}"
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all"
                                    placeholder="contact@company.com">
                            </div>
                            @error('company_email')<span class="text-rose-500 text-[10px] mt-1 ml-1 block font-bold uppercase tracking-tight">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Phone Number</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all"
                                    placeholder="+855 ...">
                            </div>
                            @error('phone')<span class="text-rose-500 text-[10px] mt-1 ml-1 block font-bold uppercase tracking-tight">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    @if($plan->price > 0)
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 ml-1">Primary Billing Cycle <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="group relative flex items-center p-4 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-blue-200 hover:bg-white transition-all peer-checked:bg-blue-50/50 peer-checked:border-blue-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 has-[:checked]:shadow-lg has-[:checked]:shadow-blue-500/5">
                                <input type="radio" name="billing_cycle" value="monthly" checked class="sr-only">
                                <div class="flex-1">
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Monthly</div>
                                    <div class="text-[11px] text-slate-500 font-bold tracking-tight uppercase">${{ number_format($plan->price, 2) }} / Month</div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600 transition-all">
                                    <i class="fa-solid fa-check text-[10px] text-white opacity-0 group-has-[:checked]:opacity-100"></i>
                                </div>
                            </label>
                            
                            <label class="group relative flex items-center p-4 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-blue-200 hover:bg-white transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 has-[:checked]:shadow-lg has-[:checked]:shadow-blue-500/5">
                                <input type="radio" name="billing_cycle" value="yearly" class="sr-only">
                                <div class="flex-1">
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Yearly</div>
                                    <div class="text-[11px] text-slate-500 font-bold tracking-tight uppercase">${{ number_format($plan->yearly_price ?? ($plan->price * 12 * 0.9), 2) }} / Year</div>
                                </div>
                                <div class="absolute -top-3 right-4 bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-tighter shadow-md">Best Value</div>
                                <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600 transition-all">
                                    <i class="fa-solid fa-check text-[10px] text-white opacity-0 group-has-[:checked]:opacity-100"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="billing_cycle" value="trial">
                    @endif
                </div>

                <!-- Section 2: Admin -->
                <div class="space-y-6 pt-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold shadow-sm">2</div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Administrator Credentials</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Full Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all"
                                    placeholder="The primary system admin">
                            </div>
                            @error('name')<span class="text-rose-500 text-[10px] mt-1 ml-1 block font-bold uppercase tracking-tight">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Login Email <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-at"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all"
                                    placeholder="admin@yourdomain.com">
                            </div>
                            @error('email')<span class="text-rose-500 text-[10px] mt-1 ml-1 block font-bold uppercase tracking-tight">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Password <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all"
                                    placeholder="••••••••">
                            </div>
                            @error('password')<span class="text-rose-500 text-[10px] mt-1 ml-1 block font-bold uppercase tracking-tight">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Confirm <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="block w-full pl-11 pr-4 py-4 border-2 border-slate-100 rounded-2xl bg-white lg:bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-semibold text-slate-900 transition-all"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black rounded-2xl text-lg shadow-[0_10px_30px_-10px_rgba(37,99,235,0.4)] hover:shadow-[0_15px_40px_-5px_rgba(37,99,235,0.5)] hover:-translate-y-1 transition-all active:scale-95 duration-300 group">
                        Initialize Your Workspace
                        <i class="fa-solid fa-rocket group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <p class="text-[11px] text-slate-400 font-bold text-center mt-8 uppercase tracking-tighter">
                        By initializing, you agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> & <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
