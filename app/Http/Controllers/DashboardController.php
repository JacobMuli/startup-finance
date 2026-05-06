<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Filters\TransactionFilter;
use Illuminate\Http\Request;

use App\Services\InsightService;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, InsightService $insightService)
    {
        // --- 1. GLOBAL SAFETY CONTEXT (Unfiltered) ---
        // Critical alerts like Runway must ignore filters for founder total safety.
        $globalQuery = Transaction::query();
        $globalExp = (clone $globalQuery)->where('type', 'OUT')
            ->selectRaw('month, MAX(date) as latest_date, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('latest_date', 'desc')
            ->take(3)
            ->pluck('total');
        
        $globalBurnRate = $globalExp->count() > 0 ? $globalExp->avg() : 0;
        $globalCash = (clone $globalQuery)->where('type', 'IN')->sum('amount') 
                    - (clone $globalQuery)->where('type', 'OUT')->sum('amount');
        $globalRunway = $globalBurnRate > 0 ? $globalCash / $globalBurnRate : null;

        // --- 2. FILTERED CONTEXT (Scenario Analysis) ---
        $baseQuery = Transaction::query();
        $baseQuery = TransactionFilter::apply($baseQuery, $request->all());

        // Core Metrics
        $totalRevenue = (clone $baseQuery)->where('type', 'IN')->sum('amount');
        $totalExpenses = (clone $baseQuery)->where('type', 'OUT')->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        // Monthly Summary
        $monthly = (clone $baseQuery)->selectRaw('month, month_date, type, SUM(amount) as total')
            ->groupBy('month', 'month_date', 'type')
            ->orderBy('month_date', 'desc')
            ->get()
            ->groupBy('month');

        $monthlySummary = $monthly->map(function ($items) {
            $in = $items->where('type', 'IN')->sum('total');
            $out = $items->where('type', 'OUT')->sum('total');

            return [
                'in' => $in,
                'out' => $out,
                'net' => $in - $out,
            ];
        })->sortKeysDesc();

        // Trend Indicators (MoM)
        $currentMonthStart = now()->startOfMonth()->format('Y-m-d');
        $prevMonthStart = now()->subMonth()->startOfMonth()->format('Y-m-d');
        $prevMonthEnd = now()->subMonth()->endOfMonth()->format('Y-m-d');

        $currentRevenue = (clone $baseQuery)->where('type', 'IN')
            ->whereDate('date', '>=', $currentMonthStart)
            ->sum('amount');
        
        $prevRevenue = (clone $baseQuery)->where('type', 'IN')
            ->whereDate('date', '>=', $prevMonthStart)
            ->whereDate('date', '<=', $prevMonthEnd)
            ->sum('amount');

        $currentExpenses = (clone $baseQuery)->where('type', 'OUT')
            ->whereDate('date', '>=', $currentMonthStart)
            ->sum('amount');
        
        $prevExpenses = (clone $baseQuery)->where('type', 'OUT')
            ->whereDate('date', '>=', $prevMonthStart)
            ->whereDate('date', '<=', $prevMonthEnd)
            ->sum('amount');

        $revenueTrend = $prevRevenue > 0 ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : null;
        $expenseTrend = $prevExpenses > 0 ? (($currentExpenses - $prevExpenses) / $prevExpenses) * 100 : null;

        // Top Category (For Dominance Detection)
        $topCategory = (clone $baseQuery)->where('type', 'OUT')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->first();

        // Forecast Data (Growth rate history)
        $growthRates = collect();
        if ($monthlySummary->count() > 1) {
            $months = $monthlySummary->values();
            for ($i = 0; $i < $months->count() - 1; $i++) {
                $curr = $months[$i]['in'];
                $prev = $months[$i+1]['in'];
                if ($prev > 0) {
                    $growthRates->push((($curr - $prev) / $prev) * 100);
                }
            }
        }

        // --- 3. INTELLIGENCE LAYER ---
        $insights = $insightService->getInsights([
            'runway' => $globalRunway, // Global context for critical alerts
            'currentRevenue' => $currentRevenue,
            'prevRevenue' => $prevRevenue,
            'currentExpenses' => $currentExpenses,
            'prevExpenses' => $prevExpenses,
            'topCategoryAmount' => $topCategory?->total ?? 0,
            'topCategoryName' => $topCategory?->category?->name ?? 'One category',
            'totalExpenses' => $totalExpenses,
            'revenueTrend' => $revenueTrend,
        ]);

        $forecasts = $insightService->getForecasts([
            'last3MonthsExpenses' => $globalExp->toArray(),
            'growthRates' => $growthRates->toArray(),
            'currentRevenue' => $currentRevenue,
        ]);

        return view('dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'monthlySummary' => $monthlySummary,
            'burnRate' => $globalBurnRate, // Use global burn for health card
            'runway' => $globalRunway,     // Use global runway for health card
            'revenueTrend' => $revenueTrend,
            'expenseTrend' => $expenseTrend,
            'insights' => $insights,
            'forecasts' => $forecasts,
            'filters' => $request->all()
        ]);
    }
}
