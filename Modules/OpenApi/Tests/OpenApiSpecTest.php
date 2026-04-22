<?php

declare(strict_types=1);

namespace Modules\OpenApi\Tests;

use OpenApi\Generator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies the OpenAPI annotations across the headless module API.
 *
 * Covers:
 *  - root @OA\Info and @OA\SecurityScheme on Modules\OpenApi\OpenApiSpec
 *  - per-controller @OA\Get/Post/Put/Delete operation annotations on
 *    every *ApiController wired into routes/module-api.php
 *
 * Full spec generation across the whole repo is exercised by artisan
 * l5-swagger:generate in CI; this test is intentionally focused on the
 * headless module surface area.
 */
class OpenApiSpecTest extends TestCase
{
    /**
     * Every *ApiController wired through routes/module-api.php and the
     * paths each one is expected to register with the OpenAPI generator.
     *
     * @var array<string, array{file:string, paths:array<int, string>}>
     */
    private const ENDPOINT_EXPECTATIONS = [
        'Content' => [
            'file' => 'Modules/Content/Http/Controllers/Api/ContentApiController.php',
            'paths' => ['/api/module/content', '/api/module/content/{id}'],
        ],
        'Pages' => [
            'file' => 'Modules/Page/Http/Controllers/Api/PageApiController.php',
            'paths' => ['/api/module/pages', '/api/module/pages/{id}'],
        ],
        'Posts' => [
            'file' => 'Modules/Post/Http/Controllers/Api/PostApiController.php',
            'paths' => ['/api/module/posts', '/api/module/posts/{id}'],
        ],
        'Tags' => [
            'file' => 'Modules/Tag/Http/Controllers/Api/TagApiController.php',
            'paths' => ['/api/module/tags', '/api/module/tags/{id}'],
        ],
        'Comments' => [
            'file' => 'Modules/Comments/Http/Controllers/Api/CommentsApiController.php',
            'paths' => ['/api/module/comments', '/api/module/comments/{id}'],
        ],
        'Menus' => [
            'file' => 'Modules/Menu/Http/Controllers/Api/MenusApiController.php',
            'paths' => ['/api/module/menus', '/api/module/menus/{id}'],
        ],
        'Media' => [
            'file' => 'Modules/Media/Http/Controllers/Api/MediaApiController.php',
            'paths' => ['/api/module/media', '/api/module/media/{id}'],
        ],
        'Forms' => [
            'file' => 'Modules/ContactForm/Http/Controllers/Api/FormsApiController.php',
            'paths' => ['/api/module/forms', '/api/module/forms/{id}'],
        ],
        'Products' => [
            'file' => 'Modules/Product/Http/Controllers/Api/ProductsApiController.php',
            'paths' => ['/api/module/products', '/api/module/products/{id}'],
        ],
        'Categories' => [
            'file' => 'Modules/Category/Http/Controllers/Api/CategoriesApiController.php',
            'paths' => ['/api/module/categories', '/api/module/categories/{id}'],
        ],
        'Orders' => [
            'file' => 'Modules/Order/Http/Controllers/Api/OrdersApiController.php',
            'paths' => ['/api/module/orders', '/api/module/orders/{id}'],
        ],
        'Coupons' => [
            'file' => 'Modules/Coupons/Http/Controllers/Api/CouponsApiController.php',
            'paths' => ['/api/module/coupons', '/api/module/coupons/{id}'],
        ],
        'Shipping' => [
            'file' => 'Modules/Shipping/Http/Controllers/Api/ShippingApiController.php',
            'paths' => ['/api/module/shipping', '/api/module/shipping/{id}'],
        ],
        'Tax' => [
            'file' => 'Modules/Tax/Http/Controllers/Api/TaxApiController.php',
            'paths' => ['/api/module/tax', '/api/module/tax/{id}'],
        ],
        'Invoices' => [
            'file' => 'Modules/Invoice/Http/Controllers/Api/InvoicesApiController.php',
            'paths' => ['/api/module/invoices', '/api/module/invoices/{id}'],
        ],
        'Customers' => [
            'file' => 'Modules/Customer/Http/Controllers/Api/CustomersApiController.php',
            'paths' => ['/api/module/customers', '/api/module/customers/{id}'],
        ],
        'Users' => [
            'file' => 'src/MicroweberPackages/User/Http/Controllers/Api/UsersApiController.php',
            'paths' => ['/api/module/users', '/api/module/users/{id}'],
        ],
        'Cart' => [
            'file' => 'Modules/Cart/Http/Controllers/Api/CartApiController.php',
            'paths' => [
                '/api/module/cart',
                '/api/module/cart/{id}',
                '/api/module/cart/totals',
                '/api/module/cart/empty',
                '/api/module/cart/coupon',
            ],
        ],
        'Checkout' => [
            'file' => 'Modules/Checkout/Http/Controllers/Api/CheckoutApiController.php',
            'paths' => [
                '/api/module/checkout',
                '/api/module/checkout/validate',
                '/api/module/checkout/shipping-methods',
                '/api/module/checkout/payment-methods',
                '/api/module/checkout/calculate-shipping',
                '/api/module/checkout/order/{orderReferenceId}',
            ],
        ],
        'Profile' => [
            'file' => 'Modules/Profile/Http/Controllers/Api/ProfileApiController.php',
            'paths' => ['/api/module/profile', '/api/module/profile/change-password'],
        ],
        'Newsletter' => [
            'file' => 'Modules/Newsletter/Http/Controllers/Api/NewsletterApiController.php',
            'paths' => [
                '/api/module/newsletter',
                '/api/module/newsletter/{id}',
                '/api/module/newsletter/unsubscribe',
            ],
        ],
        'Settings' => [
            'file' => 'Modules/Settings/Http/Controllers/Api/SettingsApiController.php',
            'paths' => ['/api/module/settings', '/api/module/settings/{key}'],
        ],
    ];

    #[Test]
    public function root_info_and_security_schemes_are_defined(): void
    {
        $json = $this->generate([base_path('Modules/OpenApi/OpenApiSpec.php')]);

        $this->assertSame('Microweber Headless API', $json['info']['title']);
        $this->assertSame('1.0.0', $json['info']['version']);
        $this->assertNotEmpty($json['info']['description']);

        $schemes = $json['components']['securitySchemes'] ?? [];

        $this->assertArrayHasKey('bearerAuth', $schemes);
        $this->assertSame('http', $schemes['bearerAuth']['type']);
        $this->assertSame('bearer', $schemes['bearerAuth']['scheme']);

        $this->assertArrayHasKey('passport', $schemes);
        $this->assertSame('oauth2', $schemes['passport']['type']);
        $this->assertArrayHasKey('authorizationCode', $schemes['passport']['flows']);
        $this->assertArrayHasKey('password', $schemes['passport']['flows']);
        $this->assertSame('/oauth/token', $schemes['passport']['flows']['password']['tokenUrl']);
        $this->assertSame('/oauth/authorize', $schemes['passport']['flows']['authorizationCode']['authorizationUrl']);
    }

    #[Test]
    public function every_module_api_controller_registers_its_paths(): void
    {
        $files = [base_path('Modules/OpenApi/OpenApiSpec.php')];
        foreach (self::ENDPOINT_EXPECTATIONS as $entry) {
            $files[] = base_path($entry['file']);
        }

        $json = $this->generate($files);
        $paths = $json['paths'] ?? [];

        foreach (self::ENDPOINT_EXPECTATIONS as $module => $entry) {
            foreach ($entry['paths'] as $expected) {
                $this->assertArrayHasKey(
                    $expected,
                    $paths,
                    "Module {$module} did not register OpenAPI path {$expected}"
                );
            }
        }
    }

    #[Test]
    public function admin_only_writes_carry_bearer_auth_security(): void
    {
        $files = [
            base_path('Modules/OpenApi/OpenApiSpec.php'),
            base_path('Modules/Content/Http/Controllers/Api/ContentApiController.php'),
            base_path('Modules/Settings/Http/Controllers/Api/SettingsApiController.php'),
            base_path('Modules/Profile/Http/Controllers/Api/ProfileApiController.php'),
        ];

        $json = $this->generate($files);

        $this->assertContains(
            ['bearerAuth' => []],
            $json['paths']['/api/module/content']['post']['security'] ?? [],
            'POST /api/module/content must require bearerAuth'
        );
        $this->assertContains(
            ['bearerAuth' => []],
            $json['paths']['/api/module/content/{id}']['delete']['security'] ?? [],
            'DELETE /api/module/content/{id} must require bearerAuth'
        );
        $this->assertContains(
            ['bearerAuth' => []],
            $json['paths']['/api/module/profile']['get']['security'] ?? [],
            'GET /api/module/profile must require bearerAuth (singleton self-serve resource)'
        );
        $this->assertContains(
            ['bearerAuth' => []],
            $json['paths']['/api/module/settings/{key}']['put']['security'] ?? [],
            'PUT /api/module/settings/{key} must require bearerAuth'
        );
    }

    /**
     * @param array<int, string> $files
     * @return array<string, mixed>
     */
    private function generate(array $files): array
    {
        $openapi = Generator::scan($files, ['validate' => false]);

        return json_decode($openapi->toJson(), true);
    }
}
