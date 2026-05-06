<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy(fn (Category $category) => $category->type . ':' . $category->name);
        $accounts = Account::all()->keyBy('name');
        $paymentMethods = PaymentMethod::all()->keyBy('name');

        $months = collect([2, 1, 0])->map(fn (int $offset) => now()->subMonths($offset)->startOfMonth());

        $serial = 1;

        foreach ($months as $monthIndex => $monthStart) {
            $monthKey = $monthStart->format('Ym');

            $rows = [
                [
                    'day' => 3,
                    'type' => 'IN',
                    'category' => 'SaaS Subscription',
                    'account' => 'Payment Gateway',
                    'payment_method' => 'Bank Transfer',
                    'source_vendor' => 'Anchor CRM Ltd',
                    'description' => 'Monthly subscription revenue',
                    'amount' => [840000, 920000, 1080000][$monthIndex],
                    'department' => 'Revenue',
                    'approved_by' => 'Finance Lead',
                ],
                [
                    'day' => 9,
                    'type' => 'IN',
                    'category' => 'Consulting',
                    'account' => 'Business Bank',
                    'payment_method' => 'Bank Transfer',
                    'source_vendor' => 'Nairobi Growth Partners',
                    'description' => 'Implementation advisory retainer',
                    'amount' => [320000, 410000, 475000][$monthIndex],
                    'department' => 'Revenue',
                    'approved_by' => 'Founder',
                ],
                [
                    'day' => 14,
                    'type' => 'IN',
                    'category' => 'Training',
                    'account' => 'M-Pesa Till',
                    'payment_method' => 'M-Pesa',
                    'source_vendor' => 'Founder Finance Workshop',
                    'description' => 'Training cohort receipts',
                    'amount' => [125000, 155000, 188000][$monthIndex],
                    'department' => 'Revenue',
                    'approved_by' => 'Finance Lead',
                ],
                [
                    'day' => 5,
                    'type' => 'OUT',
                    'category' => 'Hosting',
                    'account' => 'Business Bank',
                    'payment_method' => 'Card',
                    'source_vendor' => 'AWS',
                    'description' => 'Cloud infrastructure and backups',
                    'amount' => [118000, 132000, 149000][$monthIndex],
                    'department' => 'Engineering',
                    'approved_by' => 'CTO',
                ],
                [
                    'day' => 11,
                    'type' => 'OUT',
                    'category' => 'Marketing',
                    'account' => 'Payment Gateway',
                    'payment_method' => 'Card',
                    'source_vendor' => 'Meta Ads',
                    'description' => 'Founder acquisition campaigns',
                    'amount' => [210000, 245000, 285000][$monthIndex],
                    'department' => 'Growth',
                    'approved_by' => 'Growth Lead',
                ],
                [
                    'day' => 16,
                    'type' => 'OUT',
                    'category' => 'Software Tools',
                    'account' => 'Business Bank',
                    'payment_method' => 'Card',
                    'source_vendor' => 'GitHub, Slack, Notion',
                    'description' => 'Team productivity subscriptions',
                    'amount' => [76000, 82000, 88000][$monthIndex],
                    'department' => 'Operations',
                    'approved_by' => 'Operations Lead',
                ],
                [
                    'day' => 21,
                    'type' => 'OUT',
                    'category' => 'Operations',
                    'account' => 'Business Bank',
                    'payment_method' => 'Bank Transfer',
                    'source_vendor' => 'Founders Hub Payroll',
                    'description' => 'Contractor payroll and admin operations',
                    'amount' => [420000, 455000, 510000][$monthIndex],
                    'department' => 'Operations',
                    'approved_by' => 'Founder',
                ],
                [
                    'day' => 25,
                    'type' => 'OUT',
                    'category' => 'Transport',
                    'account' => 'M-Pesa Till',
                    'payment_method' => 'M-Pesa',
                    'source_vendor' => 'Bolt Business',
                    'description' => 'Client meeting transport',
                    'amount' => [28000, 34000, 41000][$monthIndex],
                    'department' => 'Sales',
                    'approved_by' => 'Sales Lead',
                ],
            ];

            foreach ($rows as $rowIndex => $row) {
                $date = $monthStart->copy()->day($row['day']);
                $transactionId = 'DEMO-' . $monthKey . '-' . str_pad((string) ($rowIndex + 1), 3, '0', STR_PAD_LEFT);

                Transaction::updateOrCreate(
                    ['transaction_id' => $transactionId],
                    [
                        'serial_no' => $serial++,
                        'date' => $date->toDateString(),
                        'month' => $date->format('F Y'),
                        'month_date' => $date->copy()->startOfMonth()->toDateString(),
                        'type' => $row['type'],
                        'category_id' => $categories[$row['type'] . ':' . $row['category']]->id,
                        'department' => $row['department'],
                        'source_vendor' => $row['source_vendor'],
                        'description' => $row['description'],
                        'payment_method_id' => $paymentMethods[$row['payment_method']]->id,
                        'account_id' => $accounts[$row['account']]->id,
                        'amount' => $row['amount'],
                        'approved_by' => $row['approved_by'],
                        'recorded_by' => 'Demo Seeder',
                        'transaction_tag' => 'demo-3-months',
                        'created_at' => Carbon::parse($date)->setTime(9 + ($rowIndex % 8), 0),
                        'updated_at' => Carbon::parse($date)->setTime(9 + ($rowIndex % 8), 0),
                    ]
                );
            }
        }
    }
}
