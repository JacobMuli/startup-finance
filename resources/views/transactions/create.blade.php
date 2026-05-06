<x-app-layout>
    <div class="py-6" x-data="{ 
        type: '{{ old('type', 'OUT') }}', 
        saving: false,
        amount: '{{ old('amount') }}',
        date: '{{ old('date', $defaultDate) }}',
        vendor: '{{ old('source_vendor') }}',
        selectedCategory: '{{ old('category_id', 0) }}',
        get initialCategories() { return {{ $categories->toJson() }}; },
        get suggestion() {
            if (!this.vendor || this.vendor.length < 2) return null;
            const v = this.vendor.toLowerCase();
            const rules = {
                'aws': { id: 1, name: 'Hosting' },
                'google': { id: 1, name: 'Hosting' },
                'heroku': { id: 1, name: 'Hosting' },
                'digitalocean': { id: 1, name: 'Hosting' },
                'godaddy': { id: 2, name: 'Domains' },
                'namecheap': { id: 2, name: 'Domains' },
                'facebook': { id: 3, name: 'Marketing' },
                'meta': { id: 3, name: 'Marketing' },
                'slack': { id: 4, name: 'Software Tools' },
                'github': { id: 4, name: 'Software Tools' },
                'uber': { id: 5, name: 'Transport' },
                'bolt': { id: 5, name: 'Transport' },
                'notion': { id: 10, name: 'SaaS Subscription' },
                'stripe': { id: 10, name: 'SaaS Subscription' }
            };
            for (const [key, cat] of Object.entries(rules)) {
                if (v.includes(key)) return cat;
            }
            return null;
        }
    }">
        <div class="max-w-3xl">
            <!-- Form Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Record transaction</h1>
                <p class="text-sm font-medium text-slate-500">Capture financial data with precision-grade categorization.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" @submit="saving = true">
                    @csrf

                    <div class="p-8 space-y-8">
                        <!-- Primary Flow Toggle -->
                        <div class="grid grid-cols-2 gap-4 p-1.5 bg-slate-100 rounded-xl">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="OUT" x-model="type" class="hidden">
                                <div :class="type === 'OUT' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="py-3 text-center rounded-lg text-xs font-black uppercase tracking-widest transition-all duration-200">
                                    Expense (Out)
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="IN" x-model="type" class="hidden">
                                <div :class="type === 'IN' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="py-3 text-center rounded-lg text-xs font-black uppercase tracking-widest transition-all duration-200">
                                    Revenue (In)
                                </div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Amount -->
                            <div class="col-span-full md:col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Amount (KES)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="amount" x-model="amount" required
                                        :class="type === 'IN' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                                        class="block w-full border-slate-200 bg-slate-50 rounded-xl text-lg font-black transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-xs uppercase">KES</span>
                                    </div>
                                </div>
                                @error('amount') <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase">{{ $message }}</p> @enderror
                            </div>

                            <!-- Date -->
                            <div class="col-span-full md:col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Transaction Date</label>
                                <input type="date" name="date" x-model="date" required
                                    :class="type === 'IN' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                                    class="block w-full border-slate-200 bg-slate-50 rounded-xl text-sm transition-all">
                                @error('date') <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase">{{ $message }}</p> @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-span-full">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Category Identification</label>
                                <select name="category_id" x-model="selectedCategory" required
                                    :class="type === 'IN' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                                    class="block w-full border-slate-200 bg-slate-50 rounded-xl text-sm transition-all font-bold text-slate-700">
                                    <option value="">Select Category</option>
                                    <template x-for="cat in initialCategories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name" x-show="cat.type === type" :selected="cat.id == selectedCategory"></option>
                                    </template>
                                </select>
                                @error('category_id') <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase">{{ $message }}</p> @enderror
                            </div>

                            <!-- Vendor -->
                            <div class="col-span-full md:col-span-1">
                                <div class="flex justify-between items-center mb-2">
                                   <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Entity / Vendor</label>
                                   <template x-if="suggestion && suggestion.id != selectedCategory">
                                       <button type="button" @click="selectedCategory = suggestion.id" class="text-[9px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-tighter animate-pulse">
                                           AI Suggestion: <span x-text="suggestion.name"></span> (Apply?)
                                       </button>
                                   </template>
                                </div>
                                <input type="text" name="source_vendor" x-model="vendor" placeholder="e.g. Amazon, Client Name..."
                                    :class="type === 'IN' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                                    class="block w-full border-slate-200 bg-slate-50 rounded-xl text-sm transition-all">
                            </div>

                            <!-- Account -->
                            <div class="col-span-full md:col-span-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Financial Account</label>
                                <select name="account_id"
                                    :class="type === 'IN' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                                    class="block w-full border-slate-200 bg-slate-50 rounded-xl text-sm transition-all">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="col-span-full">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ledger Note (Description)</label>
                                <textarea name="description" rows="3" placeholder="Brief context for this entry..."
                                    :class="type === 'IN' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-rose-500 focus:border-rose-500'"
                                    class="block w-full border-slate-200 bg-slate-50 rounded-xl text-sm transition-all">{{ old('description') }}</textarea>
                            </div>

                            <!-- Receipt -->
                            <div class="col-span-full">
                                <div class="flex items-center gap-2 mb-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Document Attachment</label>
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-tighter">(PDF, JPG, PNG up to 2MB)</span>
                                </div>
                                <div class="relative group">
                                    <input type="file" name="receipt_file" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer">
                                </div>
                                @error('receipt_file') <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('transactions.index') }}" class="text-xs font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Abort Entry</a>
                        <button type="submit" 
                            :disabled="saving || !amount || !date"
                            :class="saving || !amount || !date ? 'opacity-50 cursor-not-allowed' : ''"
                            class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black py-3 px-8 rounded-xl transition-all shadow-lg uppercase tracking-widest">
                            <svg x-show="saving" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="saving ? 'Processing...' : 'Secure Entry'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
