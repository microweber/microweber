<?php

namespace Tests\Feature\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets\ConversionStatsWidget;
use App\Filament\Admin\Widgets\SalesStatsWidget;
use App\Filament\Admin\Widgets\TrafficChartWidget;
use App\Filament\Admin\Widgets\TrafficStatsWidget;
use Tests\TestCase;

class AnalyticsDashboardWidgetsTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_traffic_stats_widget_exists(): void
    {
        $widget = new TrafficStatsWidget();
        $this->assertInstanceOf(TrafficStatsWidget::class, $widget);
    }

    public function test_traffic_stats_widget_has_correct_sort(): void
    {
        $this->assertEquals(1, TrafficStatsWidget::getSort());
    }

    public function test_sales_stats_widget_exists(): void
    {
        $widget = new SalesStatsWidget();
        $this->assertInstanceOf(SalesStatsWidget::class, $widget);
    }

    public function test_sales_stats_widget_has_correct_sort(): void
    {
        $this->assertEquals(2, SalesStatsWidget::getSort());
    }

    public function test_conversion_stats_widget_exists(): void
    {
        $widget = new ConversionStatsWidget();
        $this->assertInstanceOf(ConversionStatsWidget::class, $widget);
    }

    public function test_conversion_stats_widget_has_correct_sort(): void
    {
        $this->assertEquals(3, ConversionStatsWidget::getSort());
    }

    public function test_traffic_chart_widget_exists(): void
    {
        $widget = new TrafficChartWidget();
        $this->assertInstanceOf(TrafficChartWidget::class, $widget);
    }

    public function test_traffic_chart_widget_has_heading(): void
    {
        $widget = new TrafficChartWidget();
        $this->assertEquals('Traffic & Sales Trend', $widget->getHeading());
    }

    public function test_traffic_chart_widget_has_filters(): void
    {
        $widget = new TrafficChartWidget();

        $reflection = new \ReflectionClass($widget);
        $method = $reflection->getMethod('getFilters');
        $method->setAccessible(true);
        $filters = $method->invoke($widget);

        $this->assertIsArray($filters);
        $this->assertArrayHasKey('7days', $filters);
        $this->assertArrayHasKey('30days', $filters);
        $this->assertArrayHasKey('90days', $filters);
        $this->assertArrayHasKey('year', $filters);
    }

    public function test_widget_sort_orders(): void
    {
        $this->assertEquals(1, TrafficStatsWidget::getSort());
        $this->assertEquals(2, SalesStatsWidget::getSort());
        $this->assertEquals(3, ConversionStatsWidget::getSort());
        $this->assertEquals(4, TrafficChartWidget::getSort());
    }

    public function test_sales_stats_widget_formats_currency(): void
    {
        $widget = new SalesStatsWidget();

        $reflection = new \ReflectionClass($widget);
        $method = $reflection->getMethod('formatCurrency');
        $method->setAccessible(true);

        $symbolMethod = $reflection->getMethod('getCurrencySymbol');
        $symbolMethod->setAccessible(true);
        $symbol = $symbolMethod->invoke($widget);

        $this->assertEquals($symbol . '100.00', $method->invoke($widget, 100));
        $this->assertEquals($symbol . '1.0K', $method->invoke($widget, 1000));
        $this->assertEquals($symbol . '1.5M', $method->invoke($widget, 1500000));
    }

    public function test_traffic_stats_widget_formats_duration(): void
    {
        $widget = new TrafficStatsWidget();

        $reflection = new \ReflectionClass($widget);
        $method = $reflection->getMethod('formatDuration');
        $method->setAccessible(true);

        $this->assertEquals('45s', $method->invoke($widget, 45));
        $this->assertEquals('5m 30s', $method->invoke($widget, 330));
        $this->assertEquals('1h 15m', $method->invoke($widget, 4500));
    }

    public function test_traffic_stats_widget_calculates_change_percentage(): void
    {
        $widget = new TrafficStatsWidget();

        $reflection = new \ReflectionClass($widget);
        $method = $reflection->getMethod('calculateChange');
        $method->setAccessible(true);

        $this->assertEquals(100.0, $method->invoke($widget, 100, 50));
        $this->assertEquals(0.0, $method->invoke($widget, 100, 100));
        $this->assertEquals(-50.0, $method->invoke($widget, 50, 100));
        $this->assertEquals(100.0, $method->invoke($widget, 100, 0));
    }

    public function test_sales_stats_widget_calculates_change_percentage(): void
    {
        $widget = new SalesStatsWidget();

        $reflection = new \ReflectionClass($widget);
        $method = $reflection->getMethod('calculateChange');
        $method->setAccessible(true);

        $this->assertEquals(100.0, $method->invoke($widget, 100, 50));
        $this->assertEquals(0.0, $method->invoke($widget, 100, 100));
        $this->assertEquals(0.0, $method->invoke($widget, 0, 0));
    }

    public function test_conversion_stats_widget_calculates_conversion_rate(): void
    {
        $widget = new ConversionStatsWidget();

        $reflection = new \ReflectionClass($widget);
        $method = $reflection->getMethod('calculateConversionRate');
        $method->setAccessible(true);

        $rate = $method->invoke($widget, 'today');
        $this->assertIsFloat($rate);
        $this->assertGreaterThanOrEqual(0, $rate);
    }

    public function test_traffic_chart_widget_has_full_column_span(): void
    {
        $widget = new TrafficChartWidget();

        $reflection = new \ReflectionClass($widget);
        $property = $reflection->getProperty('columnSpan');
        $property->setAccessible(true);

        $this->assertEquals('full', $property->getValue($widget));
    }
}
