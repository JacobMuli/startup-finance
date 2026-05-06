<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Monthly Financial Reports') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-bold uppercase tracking-widest flex items-center">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 font-sans">
                    
                    <div class="mb-10">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Chronological Performance</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Month</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-green-600 uppercase tracking-widest">Revenue (IN)</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-red-600 uppercase tracking-widest">Expenses (OUT)</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Net Profit</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @php
                                        $totalPeriodIn = 0;
                                        $totalPeriodOut = 0;
                                    @endphp
                                    @forelse ($report as $monthKey => $items)
                                        @php
                                            $in = $items->where('type', 'IN')->sum('total');
                                            $out = $items->where('type', 'OUT')->sum('total');
                                            $net = $in - $out;
                                            
                                            $totalPeriodIn += $in;
                                            $totalPeriodOut += $out;

                                            $displayMonth = \Carbon\Carbon::createFromFormat('Y-m', $monthKey)->format('F Y');
                                        @endphp
                                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-gray-700">
                                                {{ $displayMonth }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-right font-mono text-green-600">
                                                +{{ number_format($in, 2) }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-right font-mono text-red-600">
                                                -{{ number_format($out, 2) }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-right font-mono font-bold @if($net >= 0) text-indigo-600 @else text-orange-600 @endif">
                                                {{ number_format($net, 2) }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right text-xs">
                                                <a href="{{ route('transactions.index', ['from_date' => \Carbon\Carbon::parse($monthKey)->startOfMonth()->toDateString(), 'to_date' => \Carbon\Carbon::parse($monthKey)->endOfMonth()->toDateString()]) }}" class="bg-gray-100 group-hover:bg-indigo-100 text-gray-500 group-hover:text-indigo-600 px-3 py-1 rounded-full transition-all font-bold uppercase tracking-tighter">
                                                    View Ledger
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400 italic">No financial data available for the current filters.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50/80 font-bold border-t-2 border-gray-100">
                                        <td class="px-6 py-6 text-sm text-gray-900 uppercase tracking-widest">Total for Period</td>
                                        <td class="px-6 py-6 text-right text-sm font-mono text-green-700">+{{ number_format($totalPeriodIn, 2) }}</td>
                                        <td class="px-6 py-6 text-right text-sm font-mono text-red-700">-{{ number_format($totalPeriodOut, 2) }}</td>
                                        <td class="px-6 py-6 text-right text-lg font-mono @if(($totalPeriodIn - $totalPeriodOut) >= 0) text-indigo-700 @else text-orange-700 @endif">
                                            {{ number_format($totalPeriodIn - $totalPeriodOut, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Report Export Insight -->
                    <div class="mt-8 bg-indigo-50 p-6 rounded-xl border border-indigo-100 flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-indigo-900 mb-1">Audit-Ready Reporting</h4>
                            <p class="text-xs text-indigo-600 font-medium">Use these figures for investor updates or month-end reconciliations. All data matches the ledger.</p>
                        </div>
                        <a href="{{ route('transactions.export', request()->query()) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-all shadow-md shadow-indigo-200 uppercase tracking-widest">
                            Export Detailed CSV
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
