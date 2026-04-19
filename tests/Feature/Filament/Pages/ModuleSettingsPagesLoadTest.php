<?php

namespace Tests\Feature\Filament\Pages;

use Livewire\Livewire;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Audio\Filament\AudioModuleSettings;
use Modules\Background\Filament\BackgroundModuleSettings;
use Modules\BeforeAfter\Filament\BeforeAfterModuleSettings;
use Modules\Blog\Filament\BlogSettings;
use Modules\Breadcrumb\Filament\BreadcrumbModuleSettings;
use Modules\Btn\Filament\BtnModuleSettings;
use Modules\Captcha\Filament\CaptchaModuleSettings;
use Modules\Cart\Filament\CartAddModuleSettings;
use Modules\Category\Filament\CategoryModuleSettings;
use Modules\Comments\Filament\CommentsModuleSettings;
use Modules\Comments\Filament\Pages\CommentsModuleSettingsAdmin;
use Modules\ContactForm\Filament\ContactFormModuleSettings;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\CookieNotice\Filament\Pages\CookieNoticeModuleSettingsAdmin;
use Modules\CustomFields\Filament\CustomFieldsModuleSettings;
use Modules\Embed\Filament\EmbedModuleSettings;
use Modules\FacebookLike\Filament\FacebookLikeModuleSettings;
use Modules\FacebookPage\Filament\FacebookPageModuleSettings;
use Modules\Faq\Filament\FaqModuleSettings;
use Modules\GoogleAnalytics\Filament\Pages\AdminGoogleAnalyticsSettingsPage;
use Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;
use Modules\HighlightCode\Filament\HighlightCodeModuleSettings;
use Modules\ImageRollover\Filament\ImageRolloverModuleSettings;
use Modules\LayoutContent\Filament\LayoutContentModuleSettings;
use Modules\Layouts\Filament\LayoutsModuleSettings;
use Modules\Logo\Filament\LogoModuleSettings;
use Modules\Marquee\Filament\MarqueeModuleSettings;
use Modules\Menu\Filament\MenuModuleSettings;
use Modules\Multilanguage\Filament\MultilanguageSettings;
use Modules\Multilanguage\Filament\Pages\MultilanguageSettingsAdmin;
use Modules\Newsletter\Filament\NewsletterModuleSettings;
use Modules\Page\Filament\PageModuleSettings;
use Modules\Pagination\Filament\PaginationModuleSettings;
use Modules\Pdf\Filament\PdfModuleSettings;
use Modules\Pictures\Filament\PicturesModuleSettings;
use Modules\Post\Filament\PostModuleSettings;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Rating\Filament\RatingModuleSettings;
use Modules\Search\Filament\SearchSettings;
use Modules\Sharer\Filament\SharerModuleSettings;
use Modules\Shop\Filament\ShopModuleSettings;
use Modules\Skills\Filament\SkillsModuleSettings;
use Modules\Slider\Filament\SliderModuleSettings;
use Modules\SocialLinks\Filament\SocialLinksModuleSettings;
use Modules\Spacer\Filament\SpacerModuleSettings;
use Modules\Tabs\Filament\TabsModuleSettings;
use Modules\Tag\Filament\TagsModuleSettings;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Testimonials\Filament\TestimonialsModuleSettings;
use Modules\TextType\Filament\TextTypeModuleSettings;
use Modules\TweetEmbed\Filament\TweetEmbedModuleSettings;
use Modules\Video\Filament\VideoModuleSettings;
use Modules\WhiteLabel\Filament\Pages\WhiteLabelSettingsAdminSettingsPage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class ModuleSettingsPagesLoadTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    public static function moduleSettingsProvider(): array
    {
        return [
            'accordion' => [AccordionModuleSettings::class],
            'audio' => [AudioModuleSettings::class],
            'background' => [BackgroundModuleSettings::class],
            'before_after' => [BeforeAfterModuleSettings::class],
            'blog' => [BlogSettings::class],
            'breadcrumb' => [BreadcrumbModuleSettings::class],
            'btn' => [BtnModuleSettings::class],
            'captcha' => [CaptchaModuleSettings::class],
            'cart_add' => [CartAddModuleSettings::class],
            'categories' => [CategoryModuleSettings::class],
            'comments' => [CommentsModuleSettings::class],
            'contact_form' => [ContactFormModuleSettings::class],
            'content' => [ContentModuleSettings::class],
            'cookie_notice' => [CookieNoticeModuleSettingsAdmin::class],
            'custom_fields' => [CustomFieldsModuleSettings::class],
            'embed' => [EmbedModuleSettings::class],
            'facebook_like' => [FacebookLikeModuleSettings::class],
            'facebook_page' => [FacebookPageModuleSettings::class],
            'faq' => [FaqModuleSettings::class],
            'google_maps' => [GoogleMapsModuleSettings::class],
            'highlight_code' => [HighlightCodeModuleSettings::class],
            'image_rollover' => [ImageRolloverModuleSettings::class],
            'layout_content' => [LayoutContentModuleSettings::class],
            'layouts' => [LayoutsModuleSettings::class],
            'logo' => [LogoModuleSettings::class],
            'marquee' => [MarqueeModuleSettings::class],
            'menu' => [MenuModuleSettings::class],
            'multilanguage' => [MultilanguageSettings::class],
            'newsletter' => [NewsletterModuleSettings::class],
            'pages' => [PageModuleSettings::class],
            'pagination' => [PaginationModuleSettings::class],
            'pdf' => [PdfModuleSettings::class],
            'pictures' => [PicturesModuleSettings::class],
            'posts' => [PostModuleSettings::class],
            'products' => [ProductsModuleSettings::class],
            'rating' => [RatingModuleSettings::class],
            'search' => [SearchSettings::class],
            'sharer' => [SharerModuleSettings::class],
            'shop' => [ShopModuleSettings::class],
            'skills' => [SkillsModuleSettings::class],
            'slider' => [SliderModuleSettings::class],
            'social_links' => [SocialLinksModuleSettings::class],
            'spacer' => [SpacerModuleSettings::class],
            'tabs' => [TabsModuleSettings::class],
            'tags' => [TagsModuleSettings::class],
            'teamcard' => [TeamcardModuleSettings::class],
            'testimonials' => [TestimonialsModuleSettings::class],
            'text_type' => [TextTypeModuleSettings::class],
            'tweet_embed' => [TweetEmbedModuleSettings::class],
            'video' => [VideoModuleSettings::class],
            'white_label' => [WhiteLabelSettingsAdminSettingsPage::class],
        ];
    }

    #[Test]
    #[DataProvider('moduleSettingsProvider')]
    public function it_can_render_module_settings_page(string $componentClass): void
    {
        $this->actingAsAdmin();

        Livewire::test($componentClass)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_google_analytics_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminGoogleAnalyticsSettingsPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_multilanguage_admin_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MultilanguageSettingsAdmin::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_comments_admin_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(CommentsModuleSettingsAdmin::class)
            ->assertSuccessful();
    }
}
