<?php

namespace Tests\Unit;

use App\Services\InsightService;
use PHPUnit\Framework\TestCase;

class InsightServiceTest extends TestCase
{
    private InsightService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InsightService();
    }

    public function test_it_sorts_insights_by_priority(): void
    {
        $data = [
            'runway' => 2, // Critical (P3)
            'revenueTrend' => 15, // Info (P1)
            'currentExpenses' => 1000,
            'prevExpenses' => 500, // Spike (P2)
        ];

        $insights = $this->service->getInsights($data);

        $this->assertEquals('critical', $insights[0]['type']);
        $this->assertEquals('warning', $insights[1]['type']);
        $this->assertEquals('info', $insights[2]['type']);
    }

    public function test_it_detects_low_runway_risk(): void
    {
        $data = ['runway' => 2.5];
        $insights = $this->service->getInsights($data);
        
        $this->assertTrue($insights->contains(fn($i) => $i['type'] === 'critical' && str_contains($i['message'], 'Runway')));
    }

    public function test_it_detects_revenue_plummet(): void
    {
        $data = [
            'currentRevenue' => 5000,
            'prevRevenue' => 10000, // 50% drop
        ];
        $insights = $this->service->getInsights($data);
        
        $this->assertEquals('critical', $insights[0]['type']);
        $this->assertStringContainsString('dropped', $insights[0]['message']);
    }

    public function test_it_detects_spending_spikes(): void
    {
        $data = [
            'currentExpenses' => 2000,
            'prevExpenses' => 1000, // 100% increase
        ];
        $insights = $this->service->getInsights($data);
        
        $this->assertEquals('warning', $insights[0]['type']);
        $this->assertStringContainsString('spiked', $insights[0]['message']);
    }

    public function test_it_detects_category_dominance(): void
    {
        $data = [
            'totalExpenses' => 1000,
            'topCategoryAmount' => 600, // 60%
            'topCategoryName' => 'Marketing'
        ];
        $insights = $this->service->getInsights($data);
        
        $this->assertEquals('warning', $insights[0]['type']);
        $this->assertStringContainsString('Marketing', $insights[0]['message']);
    }

    public function test_it_calculates_accurate_forecasts(): void
    {
        $data = [
            'last3MonthsExpenses' => [1000, 1200, 1400], // Avg: 1200
            'growthRates' => [10, 20], // Avg: 15%
            'currentRevenue' => 10000,
        ];
        
        $forecasts = $this->service->getForecasts($data);
        
        $this->assertEquals(1200, $forecasts['next_month_expense']);
        $this->assertEquals(11500, $forecasts['next_month_revenue']);
    }

    public function test_it_handles_no_data_gracefully(): void
    {
        $insights = $this->service->getInsights([]);
        $this->assertEquals('info', $insights[0]['type']);
        $this->assertStringContainsString('No insights yet', $insights[0]['message']);
    }
}
