<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#0F172A]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Checkout | Mekong CyberUnit</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .mesh-gradient {
            background-color: #0F172A;
            background-image: 
                radial-gradient(at 0% 0%, hsla(217,100%,13%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(224,71%,18%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(217,100%,13%,1) 0, transparent 50%),
                radial-gradient(at 50% 100%, hsla(224,71%,25%,0.5) 0, transparent 50%);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .premium-input {
            background: rgba(15, 23, 42, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.05);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-input:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #3B82F6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .grad-blue {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        }

        .step-pill {
            background: rgba(59, 130, 246, 0.1);
            color: #60A5FA;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .qr-shimmer {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .animate-in {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="h-full antialiased text-slate-300 mesh-gradient min-h-screen flex flex-col selection:bg-blue-500/30">

    <!-- Navbar -->
    <nav class="w-full bg-[#0F172A]/40 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex justify-between h-20 items-center">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-xl shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-microchip text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl tracking-tight leading-none text-white">Mekong</span>
                        <span class="font-bold text-[10px] tracking-[0.3em] uppercase text-blue-400">CyberUnit</span>
                    </div>
                </a>
                <div class="hidden md:flex items-center gap-2 text-xs font-bold text-slate-400 border border-white/5 px-4 py-2 rounded-full bg-white/5">
                    <i class="fa-solid fa-shield-halved text-blue-500"></i> 軍事級の保護: 256-BIT ENCRYPTION
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-6 py-16">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left Side: Plan Details -->
            <div class="lg:col-span-5 flex flex-col animate-in">
                <a href="/" class="text-slate-500 hover:text-blue-400 font-bold text-xs uppercase tracking-widest flex items-center gap-2 w-fit mb-10 transition-colors group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Back to Fleet
                </a>
                
                <h1 class="text-4xl font-black text-white tracking-tight mb-4 leading-tight">Finalize your <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">deployment</span></h1>
                <p class="text-slate-400 mb-8 font-medium leading-relaxed">Secure your instance on the <span class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-lg border border-blue-500/20 font-bold ml-1">{{ $plan->name }} Fleet</span>.</p>

                <!-- Billing Selection Dropdown -->
                <div class="glass-card rounded-3xl p-6 mb-8 border-l-[6px] border-l-blue-500">
                    <label for="billing-cycle-select" class="block text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-4">
                        <i class="fa-solid fa-radar mr-2"></i> Operational Mode
                    </label>
                    <div class="relative">
                        <select id="billing-cycle-select" onchange="updateCycleSelection()" 
                                class="w-full bg-[#0F172A] border-2 border-white/5 rounded-2xl px-5 py-4 font-bold text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none outline-none cursor-pointer">
                            <optgroup label="Monthly Modules">
                                <option value="1" selected>1 Month Access</option>
                                <option value="3">3 Months Access</option>
                                <option value="6">6 Months Access</option>
                            </optgroup>
                            <optgroup label="Annual Sovereignty (Recommended)">
                                <option value="12">1 Year Plan (Save 10%)</option>
                                <option value="24">2 Years Plan (Save 10%)</option>
                                <option value="36">3 Years Plan (Save 10%)</option>
                            </optgroup>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-blue-400">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary Card -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <!-- Tech background decor -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 blur-[60px] rounded-full"></div>
                    
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                        Invoice Specification
                    </h3>
                    
                    <div class="space-y-6 mb-10">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter mb-1">Infrastructure</span>
                                <span class="text-sm font-black text-white">Mekong CyberUnit System</span>
                            </div>
                            <span class="text-lg font-black text-white summary-price">${{ number_format($plan->price, 2) }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tier</span>
                                <span class="text-xs font-black text-blue-400">{{ $plan->name }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cycle</span>
                                <span class="text-xs font-black text-white" id="summary-cycle">Monthly</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Total Payload</p>
                            <p class="text-[9px] text-slate-400 italic">Encrypted and secure transaction</p>
                        </div>
                        <div class="text-4xl font-black text-white tracking-tighter">
                            <span class="summary-price text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">${{ number_format($plan->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Payment Methods -->
            <div class="lg:col-span-7 animate-in" style="animation-delay: 0.1s">
                <div class="glass-card rounded-[2.5rem] p-1 shadow-2xl overflow-hidden">
                    <div class="bg-[#1E293B]/40 rounded-[2.2rem] p-8 md:p-12 h-full">
                        
                        <!-- Payment Method Toggle -->
                        <div class="flex p-1.5 bg-[#0F172A]/80 rounded-2xl border border-white/5 mb-10">
                            <button type="button" onclick="switchMethod('khqr')" id="tab-khqr" class="flex-1 rounded-xl py-3.5 flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest transition-all duration-300 bg-blue-600 text-white shadow-lg shadow-blue-500/20">
                                <i class="fa-solid fa-qrcode text-base"></i> KHQR Pay
                            </button>
                            <button type="button" onclick="switchMethod('card')" id="tab-card" class="flex-1 rounded-xl py-3.5 flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all duration-300">
                                <i class="fa-solid fa-credit-card text-base"></i> Master/Visa
                            </button>
                        </div>
                        
                        <!-- KHQR Section -->
                        <div id="method-khqr" class="block space-y-8 animate-fade-in">
                            <div class="flex flex-col md:flex-row items-center gap-10">
                                <div class="relative group">
                                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-[3rem] blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="relative p-5 glass-card rounded-[2.5rem] bg-white border border-white/20">
                                        <div class="w-60 h-60 relative overflow-hidden rounded-2xl">
                                            <div class="qr-shimmer z-10"></div>
                                            <img src="{{ asset('images/KHQR.jpg') }}" alt="KHQR" class="w-full h-full object-cover scale-[1.3] mix-blend-multiply">
                                            <div id="qr-scanner" class="absolute top-0 left-0 w-full h-1.5 bg-blue-500 shadow-[0_0_20px_#3b82f6] z-20 hidden"></div>
                                        </div>
                                        <div class="mt-4 flex justify-center gap-2">
                                             <span class="bg-red-500 text-white text-[8px] font-black px-2 py-0.5 rounded-full">KHQR</span>
                                             <span class="bg-blue-600 text-white text-[8px] font-black px-2 py-0.5 rounded-full">BAKONG</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex-1 text-center md:text-left">
                                    <h4 class="text-xl font-black text-white mb-3">Scan to Transact</h4>
                                    <p class="text-slate-400 text-sm leading-relaxed mb-6 italic">Support all major Cambodian banks via KHQR Standard. Instant deployment after verification.</p>
                                    
                                    <div class="flex flex-col gap-2">
                                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Authorization Price</div>
                                        <div class="text-4xl font-black text-white summary-price">${{ number_format($plan->price, 2) }}</div>
                                    </div>
                                </div>
                            </div>

                            <form id="khqr-form" onsubmit="submitKHQR(event)" class="space-y-6 pt-10 border-t border-white/5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Your Full Name</label>
                                        <div class="relative">
                                            <i class="fa-solid fa-user-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                            <input type="text" id="khqr-name" required placeholder="Ex: Socheat Doem" 
                                                class="w-full pl-11 pr-4 py-4 rounded-2xl premium-input font-bold text-sm outline-none placeholder:text-slate-600">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Telegram / Phone</label>
                                        <div class="relative">
                                            <i class="fa-brands fa-telegram absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                            <input type="text" id="khqr-contact" required placeholder="@username or 012..." 
                                                class="w-full pl-11 pr-4 py-4 rounded-2xl premium-input font-bold text-sm outline-none placeholder:text-slate-600">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="btn-submit-khqr" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black rounded-2xl text-sm shadow-[0_15px_35px_-10px_rgba(37,99,235,0.4)] hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center gap-1 active:scale-95 group">
                                    <span class="flex items-center gap-2">I HAVE PAID VIA KHQR <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i></span>
                                    <span class="text-[9px] text-blue-300 uppercase tracking-widest font-bold opacity-70">Admin will verify and provision access</span>
                                </button>
                            </form>
                        </div>

                        <!-- Card Section (Disabled/Placeholder) -->
                        <div id="method-card" class="hidden space-y-8 animate-fade-in">
                             <div class="text-center py-10 glass-card rounded-3xl border-dashed border-2 border-white/10">
                                 <div class="w-20 h-20 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500 mx-auto mb-6">
                                     <i class="fa-solid fa-lock-keyhole text-3xl"></i>
                                 </div>
                                 <h4 class="text-xl font-black text-white mb-2 tracking-tight">External Gateway Blocked</h4>
                                 <p class="text-slate-400 text-sm max-w-sm mx-auto leading-relaxed">Direct card payments are currently disabled to ensure maximum KYC compliance. Please use <span class="text-blue-400 font-bold italic">KHQR Secure</span> for prompt activation.</p>
                                 <button type="button" onclick="switchMethod('khqr')" class="mt-8 px-6 py-2.5 bg-white/5 border border-white/10 rounded-full text-xs font-black uppercase text-white hover:bg-white/10 transition-all">Select KHQR Instead</button>
                             </div>
                        </div>

                        <!-- Status Overlay -->
                        <div id="success-overlay" class="absolute inset-0 bg-[#0F172A]/95 backdrop-blur-xl z-50 hidden flex-col items-center justify-center p-12 text-center rounded-[2.5rem]">
                            <div class="relative mb-10 group">
                                <div class="absolute inset-0 bg-blue-500/20 blur-[50px] animate-pulse"></div>
                                <div class="w-24 h-24 bg-[#1E293B] rounded-[2rem] border-2 border-white/10 shadow-2xl flex items-center justify-center text-5xl relative z-10 overflow-hidden transform scale-0 transition-transform duration-700" id="success-icon">
                                    <i class="fa-solid fa-radar fa-spin text-blue-500"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-white mb-3 opacity-0 transition-all duration-500 delay-200" id="success-text">Synchronizing...</h3>
                            <p class="text-slate-400 font-medium mb-10 max-w-sm opacity-0 transition-all duration-500 delay-300" id="success-sub">Establishing secure link with fleet commander. Auto-provisioning will trigger in <span id="cooldown-timer" class="font-black text-blue-400">60s</span>.</p>
                            
                            <a href="https://t.me/SOCHEAT_DOEM" target="_blank" class="hidden opacity-0 transition-all duration-700 delay-500 px-8 py-3.5 bg-blue-600/10 border border-blue-500/20 text-blue-400 font-black rounded-2xl text-sm hover:bg-blue-600/20 transition-all flex items-center gap-3" id="success-btn">
                                <i class="fa-brands fa-telegram text-lg"></i> CONTACT ADMIRAL DIRECTLY
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

    <footer class="mt-auto py-10 px-6 border-t border-white/5 bg-[#0F172A]/40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">MEKONG CYBERUNIT &copy; {{ date('Y') }} // PROTOCOL SECURED</p>
            <div class="flex gap-8">
                <a href="#" class="text-[10px] font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Privacy Port</a>
                <a href="#" class="text-[10px] font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Security Core</a>
                <a href="#" class="text-[10px] font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Service Node</a>
            </div>
        </div>
    </footer>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .scanner-animate { animation: scan 2.5s ease-in-out infinite; display: block !important; }
        @keyframes scan {
            0% { transform: translateY(0); opacity: 0; }
            5% { opacity: 1; }
            95% { opacity: 1; transform: translateY(240px); }
            100% { opacity: 0; transform: translateY(240px); }
        }
    </style>

    <script>
        let selectedMonths = 1;
        const monthlyPrice = {{ $plan->price }};
        const baseYearlyPrice = {{ $plan->yearly_price ?? ($plan->price * 12 * 0.9) }};
        const monthlyRateForYearly = baseYearlyPrice / 12;

        function updateCycleSelection() {
            const select = document.getElementById('billing-cycle-select');
            selectedMonths = parseInt(select.value);
            
            // Update summary cycle text
            const summaryCycle = document.getElementById('summary-cycle');
            if (selectedMonths === 12) summaryCycle.innerText = "1 Year";
            else if (selectedMonths === 24) summaryCycle.innerText = "2 Years";
            else if (selectedMonths === 36) summaryCycle.innerText = "3 Years";
            else summaryCycle.innerText = selectedMonths + " Months";

            let unitPrice = (selectedMonths >= 12) ? monthlyRateForYearly : monthlyPrice;
            const totalPrice = unitPrice * selectedMonths;
            
            // Update all price elements
            document.querySelectorAll('.summary-price').forEach(el => {
                el.innerText = '$' + totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            });
        }

        function switchMethod(method) {
            const tabKhqr = document.getElementById('tab-khqr');
            const tabCard = document.getElementById('tab-card');
            
            if (method === 'khqr') {
                tabKhqr.className = 'flex-1 rounded-xl py-3.5 flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest transition-all duration-300 bg-blue-600 text-white shadow-lg shadow-blue-500/20';
                tabCard.className = 'flex-1 rounded-xl py-3.5 flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all duration-300';
                document.getElementById('method-khqr').classList.remove('hidden');
                document.getElementById('method-card').classList.add('hidden');
            } else {
                tabCard.className = 'flex-1 rounded-xl py-3.5 flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest transition-all duration-300 bg-blue-600 text-white shadow-lg shadow-blue-500/20';
                tabKhqr.className = 'flex-1 rounded-xl py-3.5 flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all duration-300';
                document.getElementById('method-card').classList.remove('hidden');
                document.getElementById('method-khqr').classList.add('hidden');
            }
        }

        let pollInterval;
        let cooldown = 60;
        let pToken = '';

        function submitKHQR(e) {
            e.preventDefault();
            const scanner = document.getElementById('qr-scanner');
            const btn = document.getElementById('btn-submit-khqr');
            const name = document.getElementById('khqr-name').value;
            const contact = document.getElementById('khqr-contact').value;
            
            scanner.classList.add('scanner-animate');
            btn.innerHTML = '<span><i class="fa-solid fa-spinner fa-spin mr-2"></i> INITIATING HANDSHAKE...</span>';
            btn.classList.add('opacity-40', 'pointer-events-none');
            
            setTimeout(() => {
                notifyServerAndSuccess('KHQR', name, contact);
            }, 1200);
        }

        function notifyServerAndSuccess(method, name, contact) {
            fetch("{{ route('checkout.notify', $plan->id) }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ 
                    method: method, name: name, contact: contact,
                    billing_cycle: selectedMonths >= 12 ? 'yearly' : 'monthly',
                    months: selectedMonths,
                    total_amount: document.querySelector('.summary-price').innerText.replace('$', '').replace(',', '')
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success && data.token) {
                    pToken = data.token;
                    showWaiting();
                    startPolling();
                } else {
                    showErrorState('HANDSHAKE_FAILURE: Request rejected by node.');
                }
            }).catch(() => {
                showErrorState('COMM_ERROR: Failed to establish uplink.');
            });
        }
        
        function showWaiting() {
            const overlay = document.getElementById('success-overlay');
            const icon = document.getElementById('success-icon');
            const text = document.getElementById('success-text');
            const sub = document.getElementById('success-sub');
            
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            
            setTimeout(() => {
                icon.classList.remove('scale-0');
                text.classList.remove('opacity-0');
                sub.classList.remove('opacity-0');
            }, 50);
        }

        function startPolling() {
            const timerEl = document.getElementById('cooldown-timer');
            const intervalTimer = setInterval(() => {
                cooldown--;
                timerEl.innerText = cooldown + 's';
                if (cooldown <= 0) {
                    clearInterval(intervalTimer);
                    clearInterval(pollInterval);
                    showTimeoutState();
                }
            }, 1000);

            pollInterval = setInterval(() => {
                fetch("/checkout/{{ $plan->id }}/status/" + pToken)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'approved') {
                            clearInterval(intervalTimer);
                            clearInterval(pollInterval);
                            showApprovedState();
                        } else if (data.status === 'rejected') {
                            clearInterval(intervalTimer);
                            clearInterval(pollInterval);
                            showRejectedState();
                        }
                    }).catch(e => console.error(e));
            }, 3000);
        }

        function showApprovedState() {
            const icon = document.getElementById('success-icon');
            const text = document.getElementById('success-text');
            const sub = document.getElementById('success-sub');
            
            icon.classList.add('bg-emerald-500/20', 'border-emerald-500');
            icon.innerHTML = '<i class="fa-solid fa-check-shield text-emerald-500"></i>';
            text.innerText = "ACCESS GRANTED";
            text.className = "text-3xl font-black text-emerald-400 mb-3";
            sub.innerText = "Diverting to authorized registration node...";
            
            setTimeout(() => {
                window.location.href = "/register-company/{{ $plan->id }}?token=" + pToken;
            }, 1500);
        }

        function showRejectedState() {
            const icon = document.getElementById('success-icon');
            const text = document.getElementById('success-text');
            const sub = document.getElementById('success-sub');
            const btn = document.getElementById('success-btn');
            
            icon.classList.add('bg-rose-500/20', 'border-rose-500');
            icon.innerHTML = '<i class="fa-solid fa-ban text-rose-500"></i>';
            text.innerText = "DENIED";
            text.className = "text-3xl font-black text-rose-400 mb-3";
            sub.innerHTML = "Authorization key revoked. Manual intervention required.";
            btn.classList.remove('hidden', 'opacity-0');
        }

        function showTimeoutState() {
            const icon = document.getElementById('success-icon');
            const text = document.getElementById('success-text');
            const sub = document.getElementById('success-sub');
            const btn = document.getElementById('success-btn');
            
            icon.classList.add('bg-amber-500/20', 'border-amber-500');
            icon.innerHTML = '<i class="fa-solid fa-hourglass-clock text-amber-500"></i>';
            text.innerText = "DELAYED";
            text.className = "text-3xl font-black text-amber-500 mb-3";
            sub.innerHTML = "Commander is unavailable. Establishing secondary link via Telegram.";
            btn.classList.remove('hidden', 'opacity-0');

            pollInterval = setInterval(() => {
                fetch("/checkout/{{ $plan->id }}/status/" + pToken)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'approved') showApprovedState();
                        else if (data.status === 'rejected') { clearInterval(pollInterval); showRejectedState(); }
                    }).catch(e => console.error(e));
            }, 5000);
        }

        function showErrorState(message) {
            showWaiting();
            const icon = document.getElementById('success-icon');
            const text = document.getElementById('success-text');
            const sub = document.getElementById('success-sub');
            const btn = document.getElementById('success-btn');
            
            icon.classList.add('bg-rose-500/20', 'border-rose-500');
            icon.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-rose-500"></i>';
            text.innerText = "SYSTEM ERROR";
            text.className = "text-3xl font-black text-rose-500 mb-3";
            sub.innerHTML = message;
            btn.classList.remove('hidden', 'opacity-0');
        }
    </script>
</body>
</html>
