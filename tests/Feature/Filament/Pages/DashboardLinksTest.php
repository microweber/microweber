<?php

namespace Tests\Feature\Filament\Pages;

use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use App\Filament\Admin\Widgets\DashboardQuickStatsWidget;

class DashboardLinksTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function dashboard_quick_stats_widget_renders(): void
    {
        $this->actingAsAdmin();

        Livewire::test(DashboardQuickStatsWidget::class)
            ->assertSuccessful();
    }

    #[Test]
    public function dashboard_quick_stats_has_correct_links(): void
    {
        $this->actingAsAdmin();

        $widget = new DashboardQuickStatsWidget();
        $stats = $widget->getStats();

        $this->assertCount(4, $stats);

        $labels = array_column($stats, 'label');
        $this->assertContains('Emails', $labels);
        $this->assertContains('Last comments', $labels);
        $this->assertContains('Sales', $labels);
        $this->assertContains('Recent Orders', $labels);

        foreach ($stats as $stat) {
            $this->assertNotEmpty($stat['url'], "URL for {$stat['label']} should not be empty");
            $this->assertStringContainsString('/admin/', $stat['url'], "URL for {$stat['label']} should be an admin URL");
        }

        $emailsStat = collect($stats)->firstWhere('label', 'Emails');
        $this->assertStringContainsString('/form-entries', $emailsStat['url']);

        $commentsStat = collect($stats)->firstWhere('label', 'Last comments');
        $this->assertStringContainsString('/comments', $commentsStat['url']);

        $salesStat = collect($stats)->firstWhere('label', 'Sales');
        $this->assertStringContainsString('/orders', $salesStat['url']);

        $ordersStat = collect($stats)->firstWhere('label', 'Recent Orders');
        $this->assertStringContainsString('/orders', $ordersStat['url']);
    }

    #[Test]
    #[DataProvider('adminPageUrlsProvider')]
    public function admin_page_does_not_return_500(string $url, string $label): void
    {
        $this->actingAsAdmin();

        $response = $this->get($url);

        $this->assertNotEquals(
            500,
            $response->getStatusCode(),
            "Admin page '{$label}' at {$url} returned HTTP 500"
        );
    }

    public static function adminPageUrlsProvider(): array
    {
        $prefix = '/admin';

        return [
            'Dashboard' => ["{$prefix}", 'Dashboard'],
            'Pages' => ["{$prefix}/pages", 'Pages'],
            'Categories' => ["{$prefix}/categories", 'Categories'],
            'Posts' => ["{$prefix}/posts", 'Posts'],
            'Products' => ["{$prefix}/products", 'Products'],
            'Orders' => ["{$prefix}/orders", 'Orders'],
            'Shop Categories' => ["{$prefix}/shop-categories", 'Shop Categories'],
            'Customers' => ["{$prefix}/customers", 'Customers'],
            'Users' => ["{$prefix}/users", 'Users'],
            'Comments' => ["{$prefix}/comments", 'Comments'],
            'Form Entries' => ["{$prefix}/form-entries", 'Form Entries'],
            'Settings' => ["{$prefix}/settings", 'Settings'],
            'Modules' => ["{$prefix}/module-resource/modules", 'Modules'],
        ];
    }
}
