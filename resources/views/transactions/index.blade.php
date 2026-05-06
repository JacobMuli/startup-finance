<x-app-layout>
    <div class="py-6">
        <!-- Header Actions -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Financial Ledger</h1>
                <p class="text-sm font-medium text-slate-500">Track and manage every transaction with absolute precision.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('transactions.export', request()->query()) }}" class="inline-flex items-center gap-x-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-all duration-200">
                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    Export CSV
                </a>
                <a href="{{ route('reports.monthly') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition-all duration-200">
                    <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path d="M15.5 2A1.5 1.5 0 0014 3.5v13a1.5 1.5 0 001.5 1.5h1a1.5 1.5 0 001.5-1.5v-13A1.5 1.5 0 0016.5 2h-1zM9.5 6A1.5 1.5 0 008 7.5v9A1.5 1.5 0 009.5 18h1a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0010.5 6h-1zM3.5 10A1.5 1.5 0 002 11.5v5A1.5 1.5 0 003.5 18h1A1.5 1.5 0 006 16.5v-5A1.5 1.5 0 004.5 10h-1z" /></svg>
                    Reports
                </a>
            </div>
        </div>

        <!-- Advanced Filter Engine -->
        <div class="mb-8 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                    </div>
                    <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Filter Engine</span>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-[10px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest transition-colors">Clear All</a>
            </div>
            
            <form action="{{ route('transactions.index') }}" method="GET" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Search Ledger</label>
                        <div class="relative">
                            <input type="text" id="ledger-search" name="search" value="{{ request('search') }}" placeholder="Search ledger (press / to focus)..." class="block w-full border-slate-200 bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl text-sm transition-all pl-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Flow Type</label>
                        <select name="type" class="block w-full border-slate-200 bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl text-sm">
                            <option value="">All Transactions</option>
                            <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>Revenue (Inflow)</option>
                            <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>Expense (Outflow)</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Category</label>
                        <select name="category_id" class="block w-full border-slate-200 bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Since Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="block w-full border-slate-200 bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl text-sm">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Until Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="block w-full border-slate-200 bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl text-sm">
                    </div>

                    <!-- Account -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Financial Account</label>
                        <select name="account_id" class="block w-full border-slate-200 bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl text-sm">
                            <option value="">All Accounts</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-black py-2.5 rounded-xl transition-all shadow-md uppercase tracking-widest">Apply Intelligence</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Transaction Ledger Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="sticky top-0 bg-slate-50/95 backdrop-blur-sm z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Entity / Description</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Account</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Receipt</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($transactions as $transaction)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-150">
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-600">{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-black text-slate-900">{{ $transaction->source_vendor ?? 'General Entity' }}</div>
                                    <div class="text-[11px] font-medium text-slate-400 max-w-xs truncate">{{ $transaction->description }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[10px] font-black text-slate-600 uppercase tracking-tight ring-1 ring-inset ring-slate-200">
                                        {{ $transaction->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-slate-500 italic">{{ $transaction->account->name ?? 'Primary' }}</td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="text-sm font-black {{ $transaction->type === 'IN' ? 'text-emerald-600' : 'text-rose-500' }}">
                                        {{ $transaction->type === 'IN' ? '+' : '-' }} {{ number_format($transaction->amount, 2) }}
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">{{ $transaction->paymentMethod->name ?? 'Cash' }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @if($transaction->receipt_id)
                                        <a href="{{ route('receipts.show', $transaction->receipt_id) }}" target="_blank" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="View Document">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                        </a>
                                    @else
                                        <span class="text-slate-200">
                                            <svg class="mx-auto h-5 w-5 opacity-20" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit Record">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </a>
                                        @role('admin')
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Founders Alert: This record will be permanently purged and audited. Proceed?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Purge Record">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                        </div>
                                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">No matching transactions</h3>
                                        <p class="text-[11px] text-slate-400 mt-1 uppercase tracking-tight">Try adjusting your filters or search criteria.</p>
                                        <a href="{{ route('transactions.index') }}" class="mt-4 text-[10px] text-indigo-600 font-black uppercase underline decoration-2 underline-offset-4 decoration-indigo-200 hover:decoration-indigo-600 transition-all">Clear All Intelligence</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="p-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
