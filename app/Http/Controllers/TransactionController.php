<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Filters\TransactionFilter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with([
            'category',
            'account',
            'paymentMethod'
        ]);

        $query = TransactionFilter::apply($query, $request->all());

        $transactions = $query->latest()
            ->paginate(10)
            ->appends($request->query());

        $categories = Category::all();
        $accounts = Account::all();

        return view('transactions.index', [
            'transactions' => $transactions,
            'categories' => $categories,
            'accounts' => $accounts,
            'filters' => $request->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transactions.create', [
            'categories' => Category::all(),
            'accounts' => Account::all(),
            'paymentMethods' => PaymentMethod::all(),
            'defaultDate' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:IN,OUT',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'account_id' => 'nullable|exists:accounts,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'source_vendor' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $category = Category::find($request->category_id);

        if ($category && $category->type !== $request->type) {
            return back()->withErrors([
                'category_id' => 'Selected category does not match transaction type.'
            ])->withInput();
        }

        return DB::transaction(function () use ($request, $validated) {
            $transactionId = TransactionService::generateTransactionId();

            $transaction = Transaction::create(array_merge(
                Arr::except($validated, ['receipt_file']),
                [
                    'transaction_id' => $transactionId,
                    'month' => Carbon::parse($request->date)->format('F Y'),
                    'month_date' => Carbon::parse($request->date)->startOfMonth()->toDateString(),
                    'recorded_by' => auth()->user()->display_name ?? auth()->user()->name ?? 'system',
                ]
            ));

            // Handle receipt upload (Secure private storage)
            if ($request->hasFile('receipt_file')) {
                $file = $request->file('receipt_file');

                $filename = $transactionId . '-' . time() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs(
                    'receipts/' . now()->year . '/' . now()->format('m'),
                    $filename,
                    'local'
                );

                $receipt = \App\Models\Receipt::create([
                    'receipt_id' => $transactionId,
                    'file_path' => $path,
                ]);

                $transaction->update([
                    'receipt_id' => $receipt->id
                ]);
            }

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction recorded successfully');
        });
    }

    /**
     * Export transactions to CSV.
     */
    public function export(Request $request)
    {
        $query = Transaction::with(['category', 'account', 'paymentMethod']);
        $query = TransactionFilter::apply($query, $request->all());

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Headers
            fputcsv($handle, [
                'Transaction ID',
                'Date',
                'Month',
                'Type',
                'Category',
                'Account',
                'Payment Method',
                'Vendor',
                'Description',
                'Amount'
            ]);

            // Cursor for memory-efficient streaming
            foreach ($query->cursor() as $txn) {
                fputcsv($handle, [
                    $txn->transaction_id,
                    $txn->date,
                    $txn->month,
                    $txn->type,
                    optional($txn->category)->name,
                    optional($txn->account)->name,
                    optional($txn->paymentMethod)->name,
                    $txn->source_vendor,
                    $txn->description,
                    $txn->amount,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Display a monthly financial breakdown report.
     */
    public function monthlyReport(Request $request)
    {
        $query = Transaction::query();
        $query = TransactionFilter::apply($query, $request->all());

        // Handle different database drivers for date grouping (SQLite for testing)
        $monthSelector = DB::getDriverName() === 'sqlite' 
            ? 'strftime("%Y-%m", date) as month_key' 
            : 'DATE_FORMAT(date, "%Y-%m") as month_key';

        $report = $query->selectRaw($monthSelector . ', type, SUM(amount) as total')
            ->groupBy('month_key', 'type')
            ->orderBy('month_key', 'desc')
            ->get()
            ->groupBy('month_key');

        return view('reports.monthly', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => Category::all(),
            'accounts' => Account::all(),
            'paymentMethods' => PaymentMethod::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:IN,OUT',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'account_id' => 'nullable|exists:accounts,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'source_vendor' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $category = Category::find($request->category_id);

        if ($category && $category->type !== $request->type) {
            return back()->withErrors([
                'category_id' => 'Selected category does not match transaction type.'
            ])->withInput();
        }

        return DB::transaction(function () use ($request, $validated, $transaction) {
            $transaction->update(array_merge(
                Arr::except($validated, ['receipt_file']),
                [
                    'month' => Carbon::parse($request->date)->format('F Y'),
                    'month_date' => Carbon::parse($request->date)->startOfMonth()->toDateString(),
                ]
            ));

            if ($request->hasFile('receipt_file')) {
                $file = $request->file('receipt_file');
                $filename = $transaction->transaction_id . '-' . time() . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs(
                    'receipts/' . now()->year . '/' . now()->format('m'),
                    $filename,
                    'local'
                );

                $receipt = \App\Models\Receipt::create([
                    'receipt_id' => $transaction->transaction_id,
                    'file_path' => $path,
                ]);

                $transaction->update([
                    'receipt_id' => $receipt->id
                ]);
            }

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction updated successfully');
        });
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Simple authorization check (handled by role:admin middleware in routes)
        // This triggers the AuditLog::deleted hook and storage cleanup automatically
        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction deleted successfully (Audit Recorded)');
    }
}
