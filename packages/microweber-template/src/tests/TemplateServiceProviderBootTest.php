<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use MicroweberPackages\DbInstaller\DbInstaller;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use Templates\Bootstrap\Providers\BootstrapServiceProvider;


class TemplateServiceProviderBootTest extends TestCase
{
    public $template_name = 'Bootstrap5';


    #[Test]


    public function it_template_service_provider_is_loaded(): void {


        $is_dir = templates_dir() . $this->template_name;
        if(!$is_dir){
            return;
        }
        $templateName = $this->template_name;
        save_option('current_template', $this->template_name, 'template');

        $this->setPreserveGlobalState(false);

        app()->template_manager->setTemplateAdapter(new MicroweberTemplate());


        $current_template = app()->option_manager->get('current_template', 'template');

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $url = 'testTemplateServiceProviderIsLoaded' . uniqid();
        $newCleanPageId = save_content([
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'title' => 'testTemplateServiceProviderIsLoaded',
            'url' => $url,
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);

        app()->content_manager->define_constants(['id' => $newCleanPageId]);
        app()->template_manager->boot_template();
        $this->assertEquals($templateName, app()->template_manager->folder_name());
        $expected = BootstrapServiceProvider::class;
        $this->assertNotEmpty(app()->getProviders($expected));
        $found = false;
        $loaded = app()->getLoadedProviders();
        foreach ($loaded as $key) {
            if ($key == $expected) {
                $found = true;
            }
        }
        $this->assertTrue($found);

     }


}
