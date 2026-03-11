<x-layouts.admin>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">SaaS Subscriptions</h2>
            <p class="text-sm text-slate-500 mt-1">Overview of subscription plans and active companies</p>
        </div>
    </div>

    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-sack-dollar text-xl"></i>
                </div>
            </div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Monthly MRR</p>
            <h4 class="text-3xl font-extrabold text-slate-800">${{ number_format($monthlyIncome,2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-building-circle-check text-xl"></i>
                </div>
            </div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Active Subscriptions</p>
            <h4 class="text-3xl font-extrabold text-slate-800">{{ $activeSubscriptions }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-building-circle-xmark text-xl"></i>
                </div>
            </div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Expired Companies</p>
            <h4 class="text-3xl font-extrabold text-slate-800">{{ $expiredCompanies }}</h4>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-4">Subscription Plans</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4 border-b border-slate-100 pb-4">
                        <h6 class="text-lg font-bold text-slate-800">{{ $plan->name }}</h6>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            ${{ number_format($plan->price,2) }}/mo
                        </span>
                    </div>
                    <div class="text-sm text-slate-500 mb-4 flex gap-4">
                        <div class="flex items-center gap-1.5"><i class="fa-solid fa-users text-slate-400"></i> {{ $plan->employee_limit }} max</div>
                        <div class="flex items-center gap-1.5"><i class="fa-solid fa-building text-slate-400"></i> {{ $plan->branch_limit }} max</div>
                    </div>
                    <ul class="text-sm font-medium text-slate-600 space-y-2 mb-0 flex-1">
                        @foreach(is_array($plan->features) ? $plan->features : json_decode($plan->features ?? '[]', true) ?? [] as $feature)
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-500 mt-1 flex-shrink-0 text-xs"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Company List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h6 class="text-lg font-bold text-slate-800 mb-0">Tenant Companies</h6>
            <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                <i class="fa-solid fa-city"></i>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-6 py-4">Company Name</th>
                        <th class="px-6 py-4">Subscription Plan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Expiry Date</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($companies as $company)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $company->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $company->subscriptionPlan?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($company->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                    </span>
                                @elseif($company->status === 'expired')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5"></span> {{ ucfirst($company->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium {{ Carbon\Carbon::parse($company->expiry_date)->isPast() ? 'text-rose-600' : 'text-slate-600' }}">
                                {{ $company->expiry_date?->format('M d, Y') ?? 'Never' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="inline-flex items-center justify-center text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-md cursor-not-allowed opacity-75" disabled>Manage</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">No companies found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($companies, 'links'))
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $companies->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>
