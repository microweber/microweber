<?php

namespace Modules\Components\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Components\Filament\ComponentsModuleSettings;
use Modules\Components\Microweber\ComponentsModule;
use Modules\Components\View\Components\Alert;
use Modules\Components\View\Components\Button;
use Modules\Components\View\Components\Card;
use Modules\Components\View\Components\Checkbox;
use Modules\Components\View\Components\Col;
use Modules\Components\View\Components\Container;
use Modules\Components\View\Components\ContentCard;
use Modules\Components\View\Components\Hero;
use Modules\Components\View\Components\Input;
use Modules\Components\View\Components\Modal;
use Modules\Components\View\Components\MediaCard;
use Modules\Components\View\Components\Navbar;
use Modules\Components\View\Components\NavItem;
use Modules\Components\View\Components\LayoutSection;
use Modules\Components\View\Components\Pagination;
use Modules\Components\View\Components\PostCard;
use Modules\Components\View\Components\PricingRow;
use Modules\Components\View\Components\PricingTable;
use Modules\Components\View\Components\ProductCard;
use Modules\Components\View\Components\ProgressBar;
use Modules\Components\View\Components\Radio;
use Modules\Components\View\Components\Row;
use Modules\Components\View\Components\Section;
use Modules\Components\View\Components\Select;
use Modules\Components\View\Components\SimpleText;
use Modules\Components\View\Components\TabPane;
use Modules\Components\View\Components\Tabs;
use Modules\Components\View\Components\TeamCard;
use Modules\Components\View\Components\TestimonialCard;
use Modules\Components\View\Components\SectionHeading;
use Modules\Components\View\Components\SocialLinks;
use Modules\Components\View\Components\Cta;
use Modules\Components\View\Components\Accordion;
use Modules\Components\View\Components\AccordionItem;
use Modules\Components\View\Components\Tab;
use Modules\Components\View\Components\TabItem;
use Modules\Components\View\Components\FeatureItem;
use Modules\Components\View\Components\StatCounter;
use Modules\Components\View\Components\VideoEmbed;

class ComponentsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Components';

    protected string $moduleNameLower = 'components';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        Blade::component('hero', Hero::class);
        Blade::component('input', Input::class);
        Blade::component('checkbox', Checkbox::class);
        Blade::component('radio', Radio::class);
        Blade::component('simple-text', SimpleText::class);
        Blade::component('section', Section::class);
        Blade::component('container', Container::class);
        Blade::component('row', Row::class);
        Blade::component('col', Col::class);
        Blade::component('card', Card::class);
        Blade::component('alert', Alert::class);
        Blade::component('button', Button::class);
        Blade::component('navbar', Navbar::class);
        Blade::component('nav-item', NavItem::class);
        Blade::component('modal', Modal::class);
        Blade::component('select', Select::class);
        Blade::component('progress-bar', ProgressBar::class);
        Blade::component('tabs', Tabs::class);
        Blade::component('tab-pane', TabPane::class);
        Blade::component('layout-section', LayoutSection::class);
        Blade::component('pagination', Pagination::class);
        Blade::component('pricing-table', PricingTable::class);
        Blade::component('pricing-row', PricingRow::class);
        Blade::component('testimonial-card', TestimonialCard::class);
        Blade::component('team-card', TeamCard::class);
        Blade::component('content-card', ContentCard::class);
        Blade::component('product-card', ProductCard::class);
        Blade::component('post-card', PostCard::class);
        Blade::component('media-card', MediaCard::class);
        Blade::component('section-heading', SectionHeading::class);
        Blade::component('social-links', SocialLinks::class);
        Blade::component('cta', Cta::class);
        Blade::component('accordion', Accordion::class);
        Blade::component('accordion-item', AccordionItem::class);
        Blade::component('tab', Tab::class);
        Blade::component('tab-item', TabItem::class);
        Blade::component('feature-item', FeatureItem::class);
        Blade::component('stat-counter', StatCounter::class);
        Blade::component('video-embed', VideoEmbed::class);
    }
}
