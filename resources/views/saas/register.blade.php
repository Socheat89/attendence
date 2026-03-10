<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enterprise Registration | Mekong CyberUnit</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --blue: #3b82f6; --indigo: #6366f1; --purple: #a855f7;
            --dark: #030712; --dark2: #090e1b; --dark3: #0f172a;
            --card: rgba(15, 23, 42, 0.6);
            --border: rgba(255, 255, 255, 0.08);
            --text: #94a3b8; --white: #f8fafc;
            --blue-glow: rgba(59, 130, 246, 0.3);
            --purple-glow: rgba(168, 85, 247, 0.2);
        }
        body {
            font-family: 'Kantumruy Pro', sans-serif;
            background: var(--dark);
            color: var(--text);
            margin: 0; min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .num { font-family: 'Outfit', sans-serif; }
        
        /* ── BACKGROUND ANIMATIONS ── */
        .bg-glow {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; overflow: hidden; background: var(--dark);
        }
        .glow-1 {
            position: absolute; top: -20%; left: -10%; width: 60%; height: 70%;
            background: radial-gradient(circle, var(--blue-glow) 0%, transparent 70%);
            animation: drift 15s ease-in-out infinite alternate;
        }
        .glow-2 {
            position: absolute; bottom: -20%; right: -10%; width: 70%; height: 80%;
            background: radial-gradient(circle, var(--purple-glow) 0%, transparent 70%);
            animation: drift 20s ease-in-out infinite alternate-reverse;
        }
        @keyframes drift {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 50px) scale(1.1); }
        }

        .grad-text {
            background: linear-gradient(90deg, var(--blue), var(--purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ── GLASS CARD ── */
        .glass-card {
            background: var(--card);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid var(--border);
            border-radius: 32px;
            width: 100%;
            max-width: 900px;
            padding: 3.5rem;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5), inset 0 1px 1px rgba(255,255,255,0.05);
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group {
            position: relative;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: all 0.3s;
        }
        .input-group:focus-within {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: rgba(0, 0, 0, 0.4);
        }
        .input-field {
            background: transparent;
            border: none;
            color: var(--white);
            padding: 1.1rem 1.1rem 1.1rem 3.2rem;
            width: 100%;
            outline: none;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .input-icon {
            position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%);
            color: #475569; transition: color 0.3s;
        }
        .input-group:focus-within .input-icon { color: var(--blue); }

        /* Stop Browser Autofill from breaking the theme */
        input:-webkit-autofill {
            -webkit-background-clip: text;
            -webkit-text-fill-color: var(--white) !important;
            transition: background-color 5000s ease-in-out 0s;
            box-shadow: inset 0 0 20px 20px var(--dark3) !important;
        }

        .label-text {
            color: var(--text);
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 0.75rem;
            display: flex; align-items: center; gap: 0.6rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: white;
            padding: 1.2rem;
            border-radius: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            width: 100%;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-submit:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.6);
        }

        .step-pill {
            padding: 0.5rem 1.2rem;
            border-radius: 100px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .step-pill.active {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--blue);
            color: var(--white);
        }

        .plan-option input:checked + .plan-box {
            border-color: var(--blue);
            background: linear-gradient(180deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
            box-shadow: 0 15px 35px -5px rgba(59, 130, 246, 0.3);
            transform: translateY(-4px);
        }
        .plan-box {
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            background: rgba(255, 255, 255, 0.02);
            position: relative;
        }
    </style>
</head>
<body class="antialiased">

    <div class="bg-glow">
        <div class="glow-1"></div>
        <div class="glow-2"></div>
    </div>

    <div class="glass-card">
        <!-- Brand Header -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12 pb-8 border-b border-white/5">
            <a href="/" class="logo flex items-center gap-3 no-underline">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <i class="fa-solid fa-microchip text-lg"></i>
                </div>
                <span class="font-['Outfit'] text-2xl font-black text-white">Mekong <span class="grad-text">CyberUnit</span></span>
            </a>
            <div class="flex items-center gap-3">
                <div class="step-pill active">
                    <span class="num">01</span> Workspace
                </div>
                <div class="w-4 h-px bg-white/10"></div>
                <div class="step-pill">
                    <span class="num">02</span> Credentials
                </div>
            </div>
        </div>

        <div class="mb-10 text-center md:text-left">
            <h2 class="text-4xl font-black text-white mb-3">Setup your <span class="grad-text">Workspace</span></h2>
            <p class="text-slate-400 font-medium max-w-lg">Enter your organizational parameters below to initialize your dedicated HRM environment on our secure server.</p>
        </div>

        @if(session('error'))
        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-xs font-bold animate-pulse">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('register.company.store', $plan->id) }}" method="POST" class="space-y-10">
            @csrf

            <!-- Section 1: Company Dynamics -->
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="label-text">
                            <i class="fa-solid fa-building text-blue-500/60"></i>
                            Legal Company Entity <span class="text-blue-500 ml-auto">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-id-card input-icon"></i>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" required autofocus class="input-field" placeholder="e.g. Acme Corporation Co., Ltd">
                        </div>
                        @error('company_name')<p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="label-text">
                            <i class="fa-solid fa-envelope-open-text text-blue-500/60"></i>
                            Corporate Email
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input type="email" name="company_email" value="{{ old('company_email') }}" class="input-field" placeholder="desk@company.com">
                        </div>
                    </div>

                    <div>
                        <label class="label-text">
                            <i class="fa-solid fa-link text-blue-500/60"></i>
                            Contact Hotline
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-phone input-icon"></i>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="input-field" placeholder="+855 000 000">
                        </div>
                    </div>
                </div>

                @if($plan->price > 0)
                <div>
                    <label class="label-text">
                        <i class="fa-solid fa-credit-card text-blue-500/60"></i>
                        Billing Protocol
                    </label>
                    <div class="grid grid-cols-2 gap-5">
                        <label class="plan-option">
                            <input type="radio" name="billing_cycle" value="monthly" checked class="hidden">
                            <div class="plan-box group">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 mb-4 transition-transform group-hover:scale-110">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                                <span class="text-[10px] font-black text-slate-500 mb-1 uppercase tracking-widest block">Standard</span>
                                <span class="num text-white font-black text-2xl">${{ number_format($plan->price, 0) }}<span class="text-xs text-slate-500 font-medium">/mo</span></span>
                            </div>
                        </label>
                        <label class="plan-option">
                            <input type="radio" name="billing_cycle" value="yearly" class="hidden">
                            <div class="plan-box group">
                                <span class="absolute top-0 right-0 bg-gradient-to-l from-blue-600 to-indigo-600 text-white text-[8px] font-black px-3 py-1.5 rounded-bl-2xl uppercase tracking-tighter">Recommended</span>
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 mb-4 transition-transform group-hover:scale-110">
                                    <i class="fa-solid fa-bolt-lightning"></i>
                                </div>
                                <span class="text-[10px] font-black text-slate-500 mb-1 uppercase tracking-widest block">Annual Elite</span>
                                <span class="num text-white font-black text-2xl">${{ number_format($plan->yearly_price ?? ($plan->price * 12 * 0.9), 0) }}<span class="text-xs text-slate-500 font-medium">/yr</span></span>
                            </div>
                        </label>
                    </div>
                </div>
                @else
                    <input type="hidden" name="billing_cycle" value="trial">
                @endif
            </div>

            <!-- Section 2: Administrative Backbone -->
            <div class="space-y-8 pt-8 border-t border-white/5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="label-text">
                            <i class="fa-solid fa-user-gear text-blue-500/60"></i>
                            Master Administrator Name <span class="text-blue-500 ml-auto">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-user-tie input-icon"></i>
                            <input type="text" name="name" value="{{ old('name') }}" required class="input-field" placeholder="Primary System Controller">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-text">
                            <i class="fa-solid fa-id-badge text-blue-500/60"></i>
                            Secure Entry Email <span class="text-blue-500 ml-auto">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-at input-icon"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required class="input-field" placeholder="admin@domain.systems">
                        </div>
                    </div>

                    <div>
                        <label class="label-text">
                            <i class="fa-solid fa-key text-blue-500/60"></i>
                            Access Password <span class="text-blue-500 ml-auto">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" name="password" required class="input-field" placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <label class="label-text">
                            <i class="fa-solid fa-shield-halved text-blue-500/60"></i>
                            Confirm Protocol <span class="text-blue-500 ml-auto">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-shield-check input-icon"></i>
                            <input type="password" name="password_confirmation" required class="input-field" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="btn-submit">
                    Initialize Deployment Instance
                </button>
                <div class="mt-8 flex flex-col items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <img src="https://ui-avatars.com/api/?name=JS&background=3b82f6&color=fff" class="w-6 h-6 rounded-full border border-dark">
                            <img src="https://ui-avatars.com/api/?name=MK&background=6366f1&color=fff" class="w-6 h-6 rounded-full border border-dark">
                        </div>
                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Joined by 1,200+ Enterprise Leaders</p>
                    </div>
                    <p class="text-[8px] text-center text-slate-600 font-bold uppercase tracking-[0.2em] leading-relaxed max-w-sm">
                        Secured by Mekong CyberUnit Cryptographic Layer. All deployments are isolated and governed by MCU-9 Protocol.
                    </p>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
