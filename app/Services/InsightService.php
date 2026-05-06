<?php

namespace App\Services;

use Illuminate\Support\Collection;

class InsightService
{
    /**
     * Generate prioritized insights based on financial performance.
     *
     * @param array $data
     * @return Collection
     */
    public function getInsights(array $data): Collection
    {
        $insights = collect();

        // 1. Critical Safety (Global context ideally)
        $insights = $insights->merge($this->detectRunwayRisk($data));
        
        // 2. Performance Diagnostics
        $insights = $insights->merge($this->detectRevenueDrop($data));
        $insights = $insights->merge($this->detectSpendingSpike($data));
        $insights = $insights->merge($this->detectCategoryDominance($data));
        
        // 3. Contextual Growth
        $insights = $insights->merge($this->detectGrowthTrends($data));

        if ($insights->isEmpty()) {
            return collect([[
                'type' => 'info',
                'message' => 'No insights yet. Add more data to unlock financial intelligence.',
                'priority' => 0,
            ]]);
        }

        return $insights->sortByDesc('priority')->values();
    }

    /**
     * Calculate financial forecasts for the next month.
     *
     * @param array $data
     * @return array
     */
    public function getForecasts(array $data): array
    {
        $last3MonthsExpenses = collect($data['last3MonthsExpenses'] ?? []);
        $growthRates = collect($data['growthRates'] ?? []);
        $currentRevenue = $data['currentRevenue'] ?? 0;

        $forecastExpense = $last3MonthsExpenses->isNotEmpty() ? $last3MonthsExpenses->avg() : 0;
        
        // Revenue forecast: Apply average growth rate to current month
        $avgGrowthRate = $growthRates->isNotEmpty() ? $growthRates->avg() : 0;
        $forecastRevenue = $currentRevenue * (1 + ($avgGrowthRate / 100));

        return [
            'next_month_expense' => $forecastExpense,
            'next_month_revenue' => $forecastRevenue,
        ];
    }

    // --- Detection Methods ---

    private function detectRunwayRisk(array $data): array
    {
        $runway = $data['runway'] ?? null;

        if ($runway !== null && $runway < 3) {
            return [[
                'type' => 'critical',
                'message' => "🚨 Runway is dangerously low ({$runway} months). Prepare for survival mode.",
                'priority' => 3,
            ]];
        }

        return [];
    }

    private function detectRevenueDrop(array $data): array
    {
        $current = $data['currentRevenue'] ?? 0;
        $previous = $data['prevRevenue'] ?? 0;

        if ($previous > 0 && $current < ($previous * 0.8)) {
            $drop = round((($previous - $current) / $previous) * 100);
            return [[
                'type' => 'critical',
                'message' => "📉 Revenue dropped by {$drop}% since last month. Investigate churn immediately.",
                'priority' => 3,
            ]];
        }

        return [];
    }

    private function detectSpendingSpike(array $data): array
    {
        $current = $data['currentExpenses'] ?? 0;
        $previous = $data['prevExpenses'] ?? 0;

        if ($previous > 0 && $current > ($previous * 1.3)) {
            $spike = round((($current - $previous) / $previous) * 100);
            return [[
                'type' => 'warning',
                'message' => "⚠️ Spending spiked by {$spike}% MoM. Review operational efficiencies.",
                'priority' => 2,
            ]];
        }

        return [];
    }

    private function detectCategoryDominance(array $data): array
    {
        $topCategoryAmount = $data['topCategoryAmount'] ?? 0;
        $totalExpenses = $data['totalExpenses'] ?? 0;
        $topCategoryName = $data['topCategoryName'] ?? 'One category';

        if ($totalExpenses > 0 && $topCategoryAmount > ($totalExpenses * 0.5)) {
            return [[
                'type' => 'warning',
                'message' => "⚠️ {$topCategoryName} accounts for >50% of your total spend.",
                'priority' => 2,
            ]];
        }

        return [];
    }

    private function detectGrowthTrends(array $data): array
    {
        $revenueTrend = $data['revenueTrend'] ?? 0;

        if ($revenueTrend > 10) {
            $trend = round($revenueTrend);
            return [[
                'type' => 'info',
                'message' => "📈 Strong growth cycle: Revenue is up {$trend}% MoM.",
                'priority' => 1,
            ]];
        }

        return [];
    }
}
