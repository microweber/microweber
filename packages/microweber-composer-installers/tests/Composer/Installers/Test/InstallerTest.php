<?php

namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Config;
use Composer\Installers\Installer;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Util\Filesystem;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\IO\IOInterface;
use PHPUnit\Framework\MockObject\MockObject;

class InstallerTest extends TestCase
{
    /** @var Composer */
    private $composer;
    /** @var Config */
    private $config;
    /** @var string */
    private $vendorDir;
    /** @var string */
    private $binDir;
    /** @var InstalledRepositoryInterface&MockObject */
    private $repository;
    /** @var IOInterface */
    private $io;
    /** @var Filesystem */
    private $fs;

    public function setUp(): void
    {
        $this->fs = new Filesystem;

        $this->composer = $this->getComposer();
        $this->config = $this->composer->getConfig();

        $this->vendorDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->config->merge(array(
            'config' => array(
                'vendor-dir' => $this->vendorDir,
                'bin-dir' => $this->binDir,
            ),
        ));

        $this->repository = $this->getMockBuilder(InstalledRepositoryInterface::class)->getMock();
        $this->io = $this->getMockIO();
    }

    public function tearDown(): void
    {
        $this->fs->removeDirectory($this->vendorDir);
        $this->fs->removeDirectory($this->binDir);
    }

    /**
     * @dataProvider supportsProvider
     */
    public function testSupports(string $type, bool $expected): void
    {
        $installer = new Installer($this->io, $this->composer);
        $this->assertSame($expected, $installer->supports($type), sprintf('Failed to show support for %s', $type));
    }

    public function supportsProvider(): array
    {
        return array(
            array('agl-module', true),
            array('akaunting-module', true),
            array('annotatecms-module', true),
            array('annotatecms-component', true),
            array('annotatecms-service', true),
            array('attogram-module', true),
            array('bitrix-module', true),
            array('bitrix-component', true),
            array('bitrix-theme', true),
            array('botble-plugin', true),
            array('botble-theme', true),
            array('bonefish-package', true),
            array('cakephp', false),
            array('cakephp-', false),
            array('cakephp-app', false),
            array('cakephp-plugin', true),
            array('chef-cookbook', true),
            array('chef-role', true),
            array('cockpit-module', true),
            array('codeigniter-app', false),
            array('codeigniter-library', true),
            array('codeigniter-third-party', true),
            array('codeigniter-module', true),
            array('concrete5-block', true),
            array('concrete5-package', true),
            array('concrete5-theme', true),
            array('concrete5-core', true),
            array('concrete5-update', true),
            array('concretecms-block', true),
            array('concretecms-package', true),
            array('concretecms-theme', true),
            array('concretecms-core', true),
            array('concretecms-update', true),
            array('croogo-plugin', true),
            array('croogo-theme', true),
            array('decibel-app', true),
            array('dframe-module', true),
            array('dokuwiki-plugin', true),
            array('dokuwiki-template', true),
            array('drupal-core', true),
            array('drupal-module', true),
            array('drupal-theme', true),
            array('drupal-library', true),
            array('drupal-profile', true),
            array('drupal-database-driver', true),
            array('drupal-drush', true),
            array('drupal-custom-theme', true),
            array('drupal-custom-module', true),
            array('drupal-custom-profile', true),
            array('dolibarr-module', true),
            array('ee3-theme', true),
            array('ee3-addon', true),
            array('ee2-theme', true),
            array('ee2-addon', true),
            array('elgg-plugin', true),
            array('eliasis-component', true),
            array('eliasis-module', true),
            array('eliasis-plugin', true),
            array('eliasis-template', true),
            array('ezplatform-assets', true),
            array('ezplatform-meta-assets', true),
            array('fuel-module', true),
            array('fuel-package', true),
            array('fuel-theme', true),
            array('fuelphp-component', true),
            array('hurad-plugin', true),
            array('hurad-theme', true),
            array('imagecms-template', true),
            array('imagecms-module', true),
            array('imagecms-library', true),
            array('itop-extension', true),
            array('kanboard-plugin', true),
            array('known-plugin', true),
            array('known-theme', true),
            array('known-console', true),
            array('kohana-module', true),
            array('lms-plugin', true),
            array('lms-template', true),
            array('lms-document-template', true),
            array('lms-userpanel-module', true),
            array('laravel-library', true),
            array('lavalite-theme', true),
            array('lavalite-package', true),
            array('lithium-library', true),
            array('magento-library', true),
            array('majima-plugin', true),
            array('mako-package', true),
            array('matomo-plugin', true),
            array('mantisbt-plugin', true),
            array('miaoxing-plugin', true),
            array('modx-extra', true),
            array('modxevo-snippet', true),
            array('modxevo-plugin', true),
            array('modxevo-module', true),
            array('modxevo-template', true),
            array('modxevo-lib', true),
            array('mediawiki-extension', true),
            array('mediawiki-skin', true),
            array('microweber-module', true),
            array('modulework-module', true),
            array('moodle-mod', true),
            array('october-module', true),
            array('october-plugin', true),
            array('quicksilver-script', true),
            array('quicksilver-module', true),
            array('piwik-plugin', true),
            array('pxcms-module', true),
            array('pxcms-theme', true),
            array('phpbb-extension', true),
            array('plentymarkets-plugin', true),
            array('ppi-module', true),
            array('prestashop-module', true),
            array('prestashop-theme', true),
            array('puppet-module', true),
            array('porto-container', true),
            array('processwire-module', true),
            array('quicksilver-script', true),
            array('quicksilver-module', true),
            array('radphp-bundle', true),
            array('redaxo-addon', true),
            array('redaxo-bestyle-plugin', true),
            array('redaxo5-addon', true),
            array('redaxo5-bestyle-plugin', true),
            array('reindex-theme', true),
            array('reindex-plugin', true),
            array('roundcube-plugin', true),
            array('shopware-backend-plugin', true),
            array('shopware-core-plugin', true),
            array('shopware-frontend-plugin', true),
            array('shopware-theme', true),
            array('shopware-plugin', true),
            array('shopware-frontend-theme', true),
            array('silverstripe-module', true),
            array('silverstripe-theme', true),
            array('smf-module', true),
            array('smf-theme', true),
            array('starbug-module', true),
            array('starbug-theme', true),
            array('starbug-custom-module', true),
            array('starbug-custom-theme', true),
            array('sydes-module', true),
            array('sydes-theme', true),
            array('sylius-theme', true),
            array('tastyigniter-extension', true),
            array('tastyigniter-theme', true),
            array('thelia-module', true),
            array('thelia-frontoffice-template', true),
            array('thelia-backoffice-template', true),
            array('thelia-email-template', true),
            array('tusk-task', true),
            array('tusk-asset', true),
            array('userfrosting-sprinkle', true),
            array('vanilla-plugin', true),
            array('vanilla-theme', true),
            array('whmcs-addons', true),
            array('whmcs-fraud', true),
            array('whmcs-gateways', true),
            array('whmcs-notifications', true),
            array('whmcs-registrars', true),
            array('whmcs-reports', true),
            array('whmcs-security', true),
            array('whmcs-servers', true),
            array('whmcs-social', true),
            array('whmcs-support', true),
            array('whmcs-templates', true),
            array('whmcs-includes', true),
            array('wolfcms-plugin', true),
            array('wordpress-plugin', true),
            array('wordpress-core', false),
            array('yawik-module', true),
            array('zend-library', true),
            array('zikula-module', true),
            array('zikula-theme', true),
            array('kodicms-plugin', true),
            array('kodicms-media', true),
            array('phifty-bundle', true),
            array('phifty-library', true),
            array('phifty-framework', true),
            array('osclass-plugin', true),
            array('osclass-theme', true),
            array('osclass-language', true),
        );
    }

    /**
     * @dataProvider installPathProvider
     */
    public function testInstallPath(string $type, string $path, string $name, string $version = '1.0.0'): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package($name, $version, $version);

        $package->setType($type);
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/' . $path, $result);
    }

    public function installPathProvider(): array
    {
        return array(
            array('agl-module', 'More/MyTestPackage/', 'agl/my_test-package'),
            array('akaunting-module', 'modules/MyPackage', 'shama/MyPackage'),
            array('annotatecms-module', 'addons/modules/my_module/', 'vysinsky/my_module'),
            array('annotatecms-component', 'addons/components/my_component/', 'vysinsky/my_component'),
            array('annotatecms-service', 'addons/services/my_service/', 'vysinsky/my_service'),
            array('attogram-module', 'modules/my_module/', 'author/my_module'),
            array('bitrix-module', 'bitrix/modules/my_module/', 'author/my_module'),
            array('bitrix-component', 'bitrix/components/my_component/', 'author/my_component'),
            array('bitrix-theme', 'bitrix/templates/my_theme/', 'author/my_theme'),
            array('bitrix-d7-module', 'bitrix/modules/author.my_module/', 'author/my_module'),
            array('bitrix-d7-component', 'bitrix/components/author/my_component/', 'author/my_component'),
            array('bitrix-d7-template', 'bitrix/templates/author_my_template/', 'author/my_template'),
            array('botble-plugin', 'platform/plugins/my_plugin/', 'author/my_plugin'),
            array('botble-theme', 'platform/themes/my_theme/', 'author/my_theme'),
            array('bonefish-package', 'Packages/bonefish/package/', 'bonefish/package'),
            array('cakephp-plugin', 'Plugin/Ftp/', 'shama/ftp'),
            array('chef-cookbook', 'Chef/mre/my_cookbook/', 'mre/my_cookbook'),
            array('chef-role', 'Chef/roles/my_role/', 'mre/my_role'),
            array('cockpit-module', 'cockpit/modules/addons/My_module/', 'piotr-cz/cockpit-my_module'),
            array('codeigniter-library', 'application/libraries/my_package/', 'shama/my_package'),
            array('codeigniter-module', 'application/modules/my_package/', 'shama/my_package'),
            array('concrete5-block', 'application/blocks/concrete5_block/', 'remo/concrete5_block'),
            array('concrete5-package', 'packages/concrete5_package/', 'remo/concrete5_package'),
            array('concrete5-theme', 'application/themes/concrete5_theme/', 'remo/concrete5_theme'),
            array('concrete5-core', 'concrete/', 'concrete5/core'),
            array('concrete5-update', 'updates/concrete5/', 'concrete5/concrete5'),
            array('concretecms-block', 'application/blocks/concretecms_block/', 'remo/concretecms_block'),
            array('concretecms-package', 'packages/concretecms_package/', 'remo/concretecms_package'),
            array('concretecms-theme', 'application/themes/concretecms_theme/', 'remo/concretecms_theme'),
            array('concretecms-core', 'concrete/', 'concretecms/core'),
            array('concretecms-update', 'updates/concretecms/', 'concretecms/concretecms'),
            array('croogo-plugin', 'Plugin/Sitemaps/', 'fahad19/sitemaps'),
            array('croogo-theme', 'View/Themed/Readable/', 'rchavik/readable'),
            array('decibel-app', 'app/someapp/', 'author/someapp'),
            array('dframe-module', 'modules/author/mymodule/', 'author/mymodule'),
            array('dokuwiki-plugin', 'lib/plugins/someplugin/', 'author/someplugin'),
            array('dokuwiki-template', 'lib/tpl/sometemplate/', 'author/sometemplate'),
            array('dolibarr-module', 'htdocs/custom/my_module/', 'shama/my_module'),
            array('drupal-core', 'core/', 'drupal/core'),
            array('drupal-module', 'modules/my_module/', 'shama/my_module'),
            array('drupal-theme', 'themes/my_theme/', 'shama/my_theme'),
            array('drupal-library', 'libraries/my_library/', 'shama/my_library'),
            array('drupal-profile', 'profiles/my_profile/', 'shama/my_profile'),
            array('drupal-database-driver', 'drivers/lib/Drupal/Driver/Database/my_driver/', 'shama/my_driver'),
            array('drupal-drush', 'drush/my_command/', 'shama/my_command'),
            array('drupal-custom-theme', 'themes/custom/my_theme/', 'shama/my_theme'),
            array('drupal-custom-module', 'modules/custom/my_module/', 'shama/my_module'),
            array('drupal-custom-profile', 'profiles/custom/my_profile/', 'shama/my_profile'),
            array('elgg-plugin', 'mod/sample_plugin/', 'test/sample_plugin'),
            array('eliasis-component', 'components/my_component/', 'shama/my_component'),
            array('eliasis-module', 'modules/my_module/', 'shama/my_module'),
            array('eliasis-plugin', 'plugins/my_plugin/', 'shama/my_plugin'),
            array('eliasis-template', 'templates/my_template/', 'shama/my_template'),
            array('ee3-addon', 'system/user/addons/ee_theme/', 'author/ee_theme'),
            array('ee3-theme', 'themes/user/ee_package/', 'author/ee_package'),
            array('ee2-addon', 'system/expressionengine/third_party/ee_theme/', 'author/ee_theme'),
            array('ee2-theme', 'themes/third_party/ee_package/', 'author/ee_package'),
            array('ezplatform-assets', 'web/assets/ezplatform/ezplatform_comp/', 'author/ezplatform_comp'),
            array('ezplatform-meta-assets', 'web/assets/ezplatform/', 'author/ezplatform_comp'),
            array('fuel-module', 'fuel/app/modules/module/', 'fuel/module'),
            array('fuel-package', 'fuel/packages/orm/', 'fuel/orm'),
            array('fuel-theme', 'fuel/app/themes/theme/', 'fuel/theme'),
            array('fuelphp-component', 'components/demo/', 'fuelphp/demo'),
            array('hurad-plugin', 'plugins/Akismet/', 'atkrad/akismet'),
            array('hurad-theme', 'plugins/Hurad2013/', 'atkrad/Hurad2013'),
            array('imagecms-template', 'templates/my_template/', 'shama/my_template'),
            array('imagecms-module', 'application/modules/my_module/', 'shama/my_module'),
            array('imagecms-library', 'application/libraries/my_library/', 'shama/my_library'),
            array('itop-extension', 'extensions/my_extension/', 'shama/my_extension'),
            array('kanboard-plugin', 'plugins/my_plugin/', 'shama/my_plugin'),
            array('known-plugin', 'IdnoPlugins/SamplePlugin/', 'known/SamplePlugin'),
            array('known-theme', 'Themes/SampleTheme/', 'known/SampleTheme'),
            array('known-console', 'ConsolePlugins/SampleConsolePlugin/', 'known/SampleConsolePlugin'),
            array('kohana-module', 'modules/my_package/', 'shama/my_package'),
            array('lms-plugin', 'plugins/MyPackage/', 'shama/MyPackage'),
            array('lms-plugin', 'plugins/MyPackage/', 'shama/my_package'),
            array('lms-template', 'templates/MyPackage/', 'shama/MyPackage'),
            array('lms-template', 'templates/MyPackage/', 'shama/my_package'),
            array('lms-document-template', 'documents/templates/MyPackage/', 'shama/MyPackage'),
            array('lms-document-template', 'documents/templates/MyPackage/', 'shama/my_package'),
            array('lms-userpanel-module', 'userpanel/modules/MyPackage/', 'shama/MyPackage'),
            array('lms-userpanel-module', 'userpanel/modules/MyPackage/', 'shama/my_package'),
            array('laravel-library', 'libraries/my_package/', 'shama/my_package'),
            array('lavalite-theme', 'public/themes/my_theme/', 'shama/my_theme'),
            array('lavalite-package', 'packages/my_group/my_package/', 'my_group/my_package'),
            array('lithium-library', 'libraries/li3_test/', 'user/li3_test'),
            array('magento-library', 'lib/foo/', 'test/foo'),
            array('majima-plugin', 'plugins/MyPlugin/', 'shama/my-plugin'),
            array('modx-extra', 'core/packages/extra/', 'vendor/extra'),
            array('modxevo-snippet', 'assets/snippets/my_snippet/', 'shama/my_snippet'),
            array('modxevo-plugin', 'assets/plugins/my_plugin/', 'shama/my_plugin'),
            array('modxevo-module', 'assets/modules/my_module/', 'shama/my_module'),
            array('modxevo-template', 'assets/templates/my_template/', 'shama/my_template'),
            array('modxevo-lib', 'assets/lib/my_lib/', 'shama/my_lib'),
            array('mako-package', 'app/packages/my_package/', 'shama/my_package'),
            array('mantisbt-plugin', 'plugins/MyPlugin/', 'shama/my_plugin'),
            array('matomo-plugin', 'plugins/VisitSummary/', 'shama/visit-summary'),
            array('mediawiki-extension', 'extensions/APC/', 'author/APC'),
            array('mediawiki-extension', 'extensions/APC/', 'author/APC-extension'),
            array('mediawiki-extension', 'extensions/UploadWizard/', 'author/upload-wizard'),
            array('mediawiki-extension', 'extensions/SyntaxHighlight_GeSHi/', 'author/syntax-highlight_GeSHi'),
            array('mediawiki-skin', 'skins/someskin/', 'author/someskin-skin'),
            array('mediawiki-skin', 'skins/someskin/', 'author/someskin'),
            array('miaoxing-plugin', 'plugins/plugin/', 'shama/plugin'),
            array('miaoxing-plugin', 'plugins/my-plugin/', 'shama/my-plugin'),
            array('miaoxing-plugin', 'plugins/MyPlugin/', 'shama/MyPlugin'),
            array('miaoxing-plugin', 'plugins/my_plugin/', 'shama/my_plugin'),
            array('microweber-module', 'Modules/MyThing/', 'author/my-thing-module'),
            array('modulework-module', 'modules/my_package/', 'shama/my_package'),
            array('moodle-mod', 'mod/my_package/', 'shama/my_package'),
            array('october-module', 'modules/my_plugin/', 'shama/my_plugin'),
            array('october-plugin', 'plugins/shama/my_plugin/', 'shama/my_plugin'),
            array('october-theme', 'themes/shama-my_theme/', 'shama/my_theme'),
            array('piwik-plugin', 'plugins/VisitSummary/', 'shama/visit-summary'),
            array('prestashop-module', 'modules/a-module/', 'vendor/a-module'),
            array('prestashop-theme', 'themes/a-theme/', 'vendor/a-theme'),
            array('pxcms-module', 'app/Modules/Foo/', 'vendor/module-foo'),
            array('pxcms-module', 'app/Modules/Foo/', 'vendor/pxcms-foo'),
            array('pxcms-theme', 'themes/Foo/', 'vendor/theme-foo'),
            array('pxcms-theme', 'themes/Foo/', 'vendor/pxcms-foo'),
            array('phpbb-extension', 'ext/test/foo/', 'test/foo'),
            array('phpbb-style', 'styles/foo/', 'test/foo'),
            array('phpbb-language', 'language/foo/', 'test/foo'),
            array('plentymarkets-plugin', 'HelloWorld/', 'plugin-hello-world'),
            array('ppi-module', 'modules/foo/', 'test/foo'),
            array('puppet-module', 'modules/puppet-name/', 'puppet/puppet-name'),
            array('porto-container', 'app/Containers/container-name/', 'test/container-name'),
            array('radphp-bundle', 'src/Migration/', 'atkrad/migration'),
            array('processwire-module', 'site/modules/HelloWorld/', 'test/hello-world'),
            array('quicksilver-script', 'web/private/scripts/quicksilver/quicksilver-script', 'shama/quicksilver-script'),
            array('quicksilver-module', 'web/private/scripts/quicksilver/quicksilver-module', 'shama/quicksilver-module'),
            array('redaxo-addon', 'redaxo/include/addons/my_plugin/', 'shama/my_plugin'),
            array('redaxo-bestyle-plugin', 'redaxo/include/addons/be_style/plugins/my_plugin/', 'shama/my_plugin'),
            array('redaxo5-addon', 'redaxo/src/addons/my_plugin/', 'shama/my_plugin'),
            array('redaxo5-bestyle-plugin', 'redaxo/src/addons/be_style/plugins/my_plugin/', 'shama/my_plugin'),
            array('reindex-theme', 'themes/my_module/', 'author/my_module'),
            array('reindex-plugin', 'plugins/my_module/', 'author/my_module'),
            array('roundcube-plugin', 'plugins/base/', 'test/base'),
            array('roundcube-plugin', 'plugins/replace_dash/', 'test/replace-dash'),
            array('shopware-backend-plugin', 'engine/Shopware/Plugins/Local/Backend/ShamaMyBackendPlugin/', 'shama/my-backend-plugin'),
            array('shopware-core-plugin', 'engine/Shopware/Plugins/Local/Core/ShamaMyCorePlugin/', 'shama/my-core-plugin'),
            array('shopware-frontend-plugin', 'engine/Shopware/Plugins/Local/Frontend/ShamaMyFrontendPlugin/', 'shama/my-frontend-plugin'),
            array('shopware-theme', 'templates/my_theme/', 'shama/my-theme'),
            array('shopware-frontend-theme', 'themes/Frontend/ShamaMyFrontendTheme/', 'shama/my-frontend-theme'),
            array('shopware-plugin', 'custom/plugins/ShamaMyPlugin/', 'shama/my-plugin'),
            array('silverstripe-module', 'my_module/', 'shama/my_module'),
            array('silverstripe-module', 'sapphire/', 'silverstripe/framework', '2.4.0'),
            array('silverstripe-module', 'framework/', 'silverstripe/framework', '3.0.0'),
            array('silverstripe-module', 'framework/', 'silverstripe/framework', '3.0.0-rc1'),
            array('silverstripe-module', 'framework/', 'silverstripe/framework', 'my/branch'),
            array('silverstripe-theme', 'themes/my_theme/', 'shama/my_theme'),
            array('smf-module', 'Sources/my_module/', 'shama/my_module'),
            array('smf-theme', 'Themes/my_theme/', 'shama/my_theme'),
            array('starbug-module', 'modules/my_module/', 'shama/my_module'),
            array('starbug-theme', 'themes/my_theme/', 'shama/my_theme'),
            array('starbug-custom-module', 'app/modules/my_module/', 'shama/my_module'),
            array('starbug-custom-theme', 'app/themes/my_theme/', 'shama/my_theme'),
            array('sylius-theme', 'themes/my_theme/', 'shama/my_theme'),
            array('tastyigniter-extension', 'extensions/shama/my_extension/', 'shama/my_extension'),
            array('tastyigniter-theme', 'themes/my_theme/', 'shama/my_theme'),
            array('thelia-module', 'local/modules/my_module/', 'shama/my_module'),
            array('thelia-frontoffice-template', 'templates/frontOffice/my_template_fo/', 'shama/my_template_fo'),
            array('thelia-backoffice-template', 'templates/backOffice/my_template_bo/', 'shama/my_template_bo'),
            array('thelia-email-template', 'templates/email/my_template_email/', 'shama/my_template_email'),
            array('tusk-task', '.tusk/tasks/my_task/', 'shama/my_task'),
            array('userfrosting-sprinkle', 'app/sprinkles/my_sprinkle/', 'shama/my_sprinkle'),
            array('vanilla-plugin', 'plugins/my_plugin/', 'shama/my_plugin'),
            array('vanilla-theme', 'themes/my_theme/', 'shama/my_theme'),
            array('whmcs-addons', 'modules/addons/vendor_addon_name/', 'vendor/addon_name'),
            array('whmcs-fraud', 'modules/fraud/vendor_fraud_name/', 'vendor/fraud_name'),
            array('whmcs-gateways', 'modules/gateways/vendor_gateway_name/', 'vendor/gateway_name'),
            array('whmcs-notifications', 'modules/notifications/vendor_notification_name/', 'vendor/notification_name'),
            array('whmcs-registrars', 'modules/registrars/vendor_registrar_name/', 'vendor/registrar_name'),
            array('whmcs-reports', 'modules/reports/vendor_report_name/', 'vendor/report_name'),
            array('whmcs-security', 'modules/security/vendor_security_name/', 'vendor/security_name'),
            array('whmcs-servers', 'modules/servers/vendor_server_name/', 'vendor/server_name'),
            array('whmcs-social', 'modules/social/vendor_social_name/', 'vendor/social_name'),
            array('whmcs-support', 'modules/support/vendor_support_name/', 'vendor/support_name'),
            array('whmcs-templates', 'templates/vendor_template_name/', 'vendor/template_name'),
            array('whmcs-includes', 'includes/vendor_include_name/', 'vendor/include_name'),
            array('wolfcms-plugin', 'wolf/plugins/my_plugin/', 'shama/my_plugin'),
            array('wordpress-plugin', 'wp-content/plugins/my_plugin/', 'shama/my_plugin'),
            array('wordpress-muplugin', 'wp-content/mu-plugins/my_plugin/', 'shama/my_plugin'),
            array('zend-extra', 'extras/library/zend_test/', 'shama/zend_test'),
            array('zikula-module', 'modules/my-test_module/', 'my/test_module'),
            array('zikula-theme', 'themes/my-test_theme/', 'my/test_theme'),
            array('kodicms-media', 'cms/media/vendor/my_media/', 'shama/my_media'),
            array('kodicms-plugin', 'cms/plugins/my_plugin/', 'shama/my_plugin'),
            array('phifty-bundle', 'bundles/core/', 'shama/core'),
            array('phifty-library', 'libraries/my-lib/', 'shama/my-lib'),
            array('phifty-framework', 'frameworks/my-framework/', 'shama/my-framework'),
            array('yawik-module', 'module/MyModule/', 'shama/my_module'),
            array('osclass-plugin', 'oc-content/plugins/sample_plugin/', 'test/sample_plugin'),
            array('osclass-theme', 'oc-content/themes/sample_theme/', 'test/sample_theme'),
            array('osclass-language', 'oc-content/languages/sample_lang/', 'test/sample_lang'),
        );
    }

    public function testGetCakePHPInstallPathException(): void
    {
        $this->expectException('InvalidArgumentException');
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('shama/ftp', '1.0.0', '1.0.0');

        $package->setType('cakephp-whoops');
        $result = $installer->getInstallPath($package);
    }

    public function testCustomInstallPath(): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('shama/ftp', '1.0.0', '1.0.0');
        $package->setType('cakephp-plugin');
        $this->composer->getPackage()->setExtra(array(
            'installer-paths' => array(
                'my/custom/path/{$name}/' => array(
                    'shama/ftp',
                    'foo/bar',
                ),
            ),
        ));
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/my/custom/path/Ftp/', $result);
    }

    public function testCustomInstallerName(): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('shama/cakephp-ftp-plugin', '1.0.0', '1.0.0');
        $package->setType('cakephp-plugin');
        $package->setExtra(array(
            'installer-name' => 'FTP',
        ));
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/Plugin/FTP/', $result);
    }

    public function testCustomTypePath(): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('slbmeh/my_plugin', '1.0.0', '1.0.0');
        $package->setType('wordpress-plugin');
        $this->composer->getPackage()->setExtra(array(
            'installer-paths' => array(
                'my/custom/path/{$name}/' => array(
                    'type:wordpress-plugin'
                ),
            ),
        ));
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/my/custom/path/my_plugin/', $result);
    }

    public function testVendorPath(): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('penyaskito/my_module', '1.0.0', '1.0.0');
        $package->setType('drupal-module');
        $this->composer->getPackage()->setExtra(array(
          'installer-paths' => array(
            'modules/custom/{$name}/' => array(
              'vendor:penyaskito'
            ),
          ),
        ));
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/modules/custom/my_module/', $result);
    }

    public function testStringPath(): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('penyaskito/my_module', '1.0.0', '1.0.0');
        $package->setType('drupal-module');
        $this->composer->getPackage()->setExtra(array(
          'installer-paths' => array(
            'modules/custom/{$name}/' => 'vendor:penyaskito',
          ),
        ));
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/modules/custom/my_module/', $result);
    }

    public function testNoVendorName(): void
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package('vanillaPlugin', '1.0.0', '1.0.0');

        $package->setType('vanilla-plugin');
        $result = $installer->getInstallPath($package);
        $this->assertEquals(getcwd() . '/plugins/vanillaPlugin/', $result);
    }

    public function testUninstallAndDeletePackageFromLocalRepo(): void
    {
        $package = new Package('foo', '1.0.0', '1.0.0');

        $installer = $this->getMockBuilder(Installer::class)
            ->setMethods(array('getInstallPath', 'removeCode'))
            ->setConstructorArgs(array($this->io, $this->composer))
            ->getMock();
        $installer->expects($this->atLeastOnce())->method('getInstallPath')->with($package)->will($this->returnValue(sys_get_temp_dir().'/foo'));
        $installer->expects($this->atLeastOnce())->method('removeCode')->with($package)->will($this->returnValue(null));

        $repo = $this->repository;
        $repo->expects($this->once())->method('hasPackage')->with($package)->will($this->returnValue(true));
        $repo->expects($this->once())->method('removePackage')->with($package);

        $installer->uninstall($repo, $package);
    }

    /**
     * @dataProvider disabledInstallersProvider
     * @param mixed $disabled
     */
    public function testDisabledInstallers($disabled, string $type, bool $expected): void
    {
        $this->composer->getPackage()->setExtra(array(
            'installer-disable' => $disabled,
        ));
        $this->testSupports($type, $expected);
    }

    public function disabledInstallersProvider(): array
    {
        return array(
            array(false, "drupal-module", true),
            array(true, "drupal-module", false),
            array("true", "drupal-module", true),
            array("all", "drupal-module", false),
            array("*", "drupal-module", false),
            array("cakephp", "drupal-module", true),
            array("drupal", "cakephp-plugin", true),
            array("cakephp", "cakephp-plugin", false),
            array("drupal", "drupal-module", false),
            array(array("drupal", "cakephp"), "cakephp-plugin", false),
            array(array("drupal", "cakephp"), "drupal-module", false),
            array(array("cakephp", true), "drupal-module", false),
            array(array("cakephp", "all"), "drupal-module", false),
            array(array("cakephp", "*"), "drupal-module", false),
            array(array("cakephp", "true"), "drupal-module", true),
            array(array("drupal", "true"), "cakephp-plugin", true),
        );
    }
}
