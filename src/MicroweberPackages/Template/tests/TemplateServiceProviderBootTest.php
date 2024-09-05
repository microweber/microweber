<?php

namespace MicroweberPackages\Template\tests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Install\DbInstaller;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class TemplateServiceProviderBootTest extends TestCase
{
    public $template_name = 'big';

    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    public function testTemplateServiceProviderIsLoaded()
    {


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
        $expected = 'MicroweberPackages\Template\Big\TemplateServiceProvider';
        $this->assertNotEmpty(app()->getProviders($expected));
        $found = false;
        $loaded = app()->getLoadedProviders();
        foreach ($loaded as $key) {
            if ($key == $expected) {
                $found = true;
            }
        }
        $this->assertTrue($found);

        $installer = new DbInstaller();
         $installer->createSchema();


        // check for migration in db table
        $files = ['2021_08_24_132521_update_new_world_template_edit_field_names','2021_03_04_000001_update_template_edit_field_names'];
        $check = DB::table('migrations')->whereIn('migration', $files)->first();
        $this->assertNotEmpty($check);
     }


}
