<x-app-layout>
    <div class="py-6">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Founder Dashboard</h1>
            <p class="text-sm font-medium text-slate-500">Monitor runway, burn, and cash flow across the business.</p>
        </div>

        <!-- 🚨 Intelligence & Insights Panel -->
        @if($insights->isNotEmpty())
            <div class="mb-8 space-y-3">
                @foreach($insights as $insight)
                    <div class="flex items-start gap-4 p-4 rounded-2xl border transition-all shadow-sm 
                        @if($insight['type'] === 'critical') bg-rose-50 border-rose-200 text-rose-800 critical-alert
                        @elseif($insight['type'] === 'warning') bg-amber-50 border-amber-200 text-amber-800
                        @else bg-indigo-50 border-indigo-200 text-indigo-800
                        @endif">
                        <div class="shrink-0 mt-0.5">
                            @if($insight['type'] === 'critical')
                                <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            @elseif($insight['type'] === 'warning')
                                <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            @else
                                <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-black uppercase tracking-widest mb-0.5">{{ $insight['type'] }} report</p>
                            <p class="text-sm font-bold leading-relaxed">{{ $insight['message'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <!-- Dashboard Filters -->
        <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-indigo-50 rounded-lg">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Analysis Period</h3>
                    </div>
                    <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="text-sm border-slate-200 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50">
                        <span class="text-slate-400 font-medium">—</span>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="text-sm border-slate-200 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 px-5 rounded-lg transition-all shadow-sm uppercase tracking-widest">Update</button>
                        @if(request()->has('from_date') || request()->has('to_date'))
                            <a href="{{ route('dashboard') }}" class="text-xs text-slate-500 hover:text-rose-500 font-bold uppercase tracking-widest transition-colors">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard', ['from_date' => now()->startOfMonth()->format('Y-m-d'), 'to_date' => now()->endOfMonth()->format('Y-m-d')]) }}" class="text-[11px] bg-white hover:bg-slate-50 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200 transition-all uppercase font-bold tracking-wider shadow-sm">This Month</a>
                    <a href="{{ route('dashboard', ['from_date' => now()->subMonths(3)->startOfMonth()->format('Y-m-d'), 'to_date' => now()->endOfMonth()->format('Y-m-d')]) }}" class="text-[11px] bg-white hover:bg-slate-50 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200 transition-all uppercase font-bold tracking-wider shadow-sm">Q1 Snapshot</a>
                </div>
            </div>
        </div>

        <!-- 1. Core Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Revenue Card -->
            <a href="{{ route('transactions.index', array_merge(request()->all(), ['type' => 'IN'])) }}" class="block group hover-premium">
                <div class="bg-white overflow-hidden rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="h-16 w-16 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                    </div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Revenue</div>
                        @if($revenueTrend !== null)
                            <div class="flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold {{ $revenueTrend >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}" title="Compared to previous month performance">
                                {{ $revenueTrend >= 0 ? '↑' : '↓' }} {{ abs(round($revenueTrend, 1)) }}%
                            </div>
                        @endif
                    </div>
                    <div class="mt-auto">
                        <div class="text-2xl font-black text-slate-900">KES {{ number_format($totalRevenue, 2) }}</div>
                        <div class="mt-2 text-[10px] font-bold text-emerald-600 uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">View Details →</div>
                    </div>
                </div>
            </a>

            <!-- Expenses Card -->
            <a href="{{ route('transactions.index', array_merge(request()->all(), ['type' => 'OUT'])) }}" class="block group hover-premium">
                <div class="bg-white overflow-hidden rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="h-16 w-16 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.306-4.307a11.95 11.95 0 015.814 5.519l2.74 1.22m0 0l-5.94 2.28m5.94 2.28l-2.28 5.941" /></svg>
                    </div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Expenses</div>
                        @if($expenseTrend !== null)
                            <div class="flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold {{ $expenseTrend <= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}" title="Compared to previous month performance">
                                {{ $expenseTrend <= 0 ? '↓' : '↑' }} {{ abs(round($expenseTrend, 1)) }}%
                            </div>
                        @endif
                    </div>
                    <div class="mt-auto">
                        <div class="text-2xl font-black text-slate-900">KES {{ number_format($totalExpenses, 2) }}</div>
                        <div class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">View Details →</div>
                    </div>
                </div>
            </a>

            <!-- Net Profit Card -->
            <div class="bg-slate-900 overflow-hidden rounded-2xl border border-slate-800 p-6 shadow-xl flex flex-col h-full hover-premium">
                <div class="flex justify-between items-start mb-8">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest font-mono">Net Position</div>
                    <div class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></div>
                </div>
                <div class="mt-auto">
                    <div class="text-3xl font-black text-white">KES {{ number_format($netProfit, 2) }}</div>
                    <p class="mt-1 text-[10px] text-slate-400 uppercase tracking-widest">Consolidated Cash Flow</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- 2. Monthly Trend Table -->
            <div class="lg:col-span-2 bg-white overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Monthly Performance</h3>
                        <div class="h-px flex-1 bg-slate-100 mx-6"></div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th class="pb-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Period</th>
                                    <th class="pb-4 text-left text-[10px] font-black text-emerald-600 uppercase tracking-widest">Revenue</th>
                                    <th class="pb-4 text-left text-[10px] font-black text-rose-500 uppercase tracking-widest">Expense</th>
                                    <th class="pb-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Net Cash</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($monthlySummary as $month => $data)
                                    <tr class="group hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('transactions.index', ['from_date' => \Carbon\Carbon::parse($month)->startOfMonth()->format('Y-m-d'), 'to_date' => \Carbon\Carbon::parse($month)->endOfMonth()->format('Y-m-d')]) }}'">
                                        <td class="py-5 whitespace-nowrap text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $month }}</td>
                                        <td class="py-5 whitespace-nowrap text-sm text-emerald-600 font-bold">+{{ number_format($data['in'], 0) }}</td>
                                        <td class="py-5 whitespace-nowrap text-sm text-rose-500 font-bold">-{{ number_format($data['out'], 0) }}</td>
                                        <td class="py-5 whitespace-nowrap text-sm font-black text-right {{ $data['net'] >= 0 ? 'text-slate-900' : 'text-rose-600' }}">
                                            {{ number_format($data['net'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="h-12 w-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                                                </div>
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No financial data yet.</p>
                                                <div class="mt-2 flex flex-col items-center">
                                                    <a href="{{ route('transactions.create') }}" class="text-[10px] text-emerald-600 font-black uppercase underline decoration-2 underline-offset-4">Record First Transaction</a>
                                                    <span class="mt-1 text-[8px] font-black text-slate-300 uppercase tracking-tighter">(Or press "N" from anywhere)</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Startup Health Metrics -->
            <div class="bg-white overflow-hidden rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <div class="p-8 flex-1">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-10">Startup Health</h3>
                    
                    <div class="mb-10 p-5 bg-rose-50/30 rounded-2xl border border-rose-100/50">
                        <div class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Monthly Burn (Avg)</div>
                        <div class="text-2xl font-black text-rose-600">KES {{ number_format($burnRate, 2) }}</div>
                        <p class="text-[9px] text-rose-400 mt-1 uppercase leading-tight font-medium">Outflow velocity based on last 3 months</p>
                    </div>

                    <div class="p-5 bg-indigo-50/30 rounded-2xl border border-indigo-100/50 relative overflow-hidden mb-6">
                        <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Survival Runway</div>
                        @if($burnRate > 0)
                            <div class="text-4xl font-black text-indigo-600">
                                {{ round($runway, 1) }} <span class="text-sm font-bold text-indigo-400 lowercase italic">mo</span>
                            </div>
                        @else
                            <div class="text-xl font-black text-emerald-600 uppercase italic">Infinite / No Burn</div>
                        @endif
                        <p class="text-[9px] text-indigo-400 mt-2 uppercase leading-tight font-medium tracking-tight">Time remaining at current burn trajectory</p>
                    </div>

                    <!-- 🔮 Predictive Forecasts -->
                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Predictive Forecast (Next 30d)</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-[9px] font-bold text-slate-500 uppercase">Est. Revenue</span>
                                    <span class="text-xs font-black text-emerald-600">KES {{ number_format($forecasts['next_month_revenue'], 0) }}</span>
                                </div>
                                <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full" style="width: 65%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-[9px] font-bold text-slate-500 uppercase">Est. Expenses</span>
                                    <span class="text-xs font-black text-rose-600">KES {{ number_format($forecasts['next_month_expense'], 0) }}</span>
                                </div>
                                <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
                                    <div class="bg-rose-500 h-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Quick Access -->
                <div class="p-8 pt-0">
                    <a href="{{ route('reports.monthly') }}" class="group flex items-center justify-between bg-slate-900 hover:bg-slate-800 px-5 py-4 rounded-xl transition-all shadow-lg">
                        <span class="text-xs font-black text-white uppercase tracking-widest">Financial Reports</span>
                        <svg class="h-4 w-4 text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
