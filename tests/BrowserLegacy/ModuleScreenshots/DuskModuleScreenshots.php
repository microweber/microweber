<?php

namespace Tests\BrowserLegacy\ModuleScreenshots;

use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use SapientPro\ImageComparator\ImageComparator;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

abstract class DuskModuleScreenshots extends DuskTestCase
{
    public $template_name = 'Big2';

    public function bootTemplate()
    {
        if (defined('TEMPLATE_DIR') == false) {
            define('TEMPLATE_DIR', templates_path() . $this->template_name . DS);
        }

        app()->template_manager->boot_template();

        save_option('current_template', $this->template_name, 'template');
}

#[Test]
public function it_creates_full_screenshot(): void
    {
        $this->bootTemplate();

        $tempaltePathMain = 'Templates' . DS . $this->template_name;
//        $sample = $tempaltePathMain . '/mw_default_content.zip';
//        $sample = normalize_path($sample, false);
//
//        if (!is_file($sample)) {
//            $this->markTestSkipped('File not found for template test: ' . $sample);
//        }

//        // START BACKUP
//        Option::truncate();
//        Menu::truncate();
//        Content::truncate();
//        ContentField::truncate();
//        $sessionId = SessionStepper::generateSessionId(0);
//        $manager = new Import();
//        $manager->setSessionId($sessionId);
//        $manager->setFile($sample);
//        $manager->setBatchImporting(false);
//        $manager->setToDeleteOldContent(true);
//        $manager->setToDeleteOldCssFiles(true);
//        $manager->setOvewriteById(true);
//        $importStatus = $manager->start();
//        $this->assertTrue($importStatus['done']);
//        clearcache();
//        // END BACKUP

        $pageLinks = [];
        $getPages = \Modules\Page\Models\Page::get();
        foreach ($getPages as $page) {
            $pageLinks[] = $page->url;
        }

//        foreach ($pageLinks as $pageLink) {
//            $this->browse(function (Browser $browser) use ($tempaltePathMain, $pageLinks, $pageLink) {
//
//                $browser->visit($pageLink);
//
//                $body = $browser->driver->findElement(WebDriverBy::tagName('body'));
//                if (!empty($body)) {
//
//                    $browser->script("document.body.classList.add('js-dusk-browser-test')");
//
//                    $currentSize = $body->getSize();
//                    //set window to full height
//                    $size = new WebDriverDimension(1300, $currentSize->getHeight());
//                    $browser->driver->manage()->window()->setSize($size);
//                }
//
//                $browser->pause(5000);
//
//                $browser->driver->takeScreenshot($tempaltePathMain . '/screenshots/' . $pageLink . '.png');
//
//            });
//        }
//
//        $this->browse(function (Browser $browser) use($tempaltePathMain) {
//            $browser->visit('/');
//            $browser->resize(1360, 800);
//            $browser->pause(5000);
//
//            $screenshotFile = $tempaltePathMain . '/screenshot.png';
//            $browser->driver->takeScreenshot($screenshotFile);
//
//            $tn = new \Modules\Media\Support\Thumbnailer($screenshotFile);
//            $tn->createThumb(array('width' => 820, 'height'=>460), $screenshotFile);
//
//        });

//        $this->browse(function (Browser $browser) use($tempaltePathMain) {
//
//            $browser->visit('/');
//
//            $body = $browser->driver->findElement(WebDriverBy::tagName('body'));
//            if (!empty($body)) {
//
//                $browser->script("document.body.classList.add('js-dusk-browser-test')");
//
//                $currentSize = $body->getSize();
//                //set window to full height
//                $size = new WebDriverDimension(1300, $currentSize->getHeight());
//                $browser->driver->manage()->window()->setSize($size);
//            }
//
//            $browser->pause(5000);
//
//            $browser->driver->takeScreenshot($tempaltePathMain . '/screenshot_large.png');
//        });


}

#[Test]
public function it_creates_modules_screenshots(): void
    {


        $this->bootTemplate();
//        $modules = get_modules('ui=1');

        $modules = app()->microweber->getModules();

//        $modules = [
//          [
//              'module'=>'teamcard'
//          ]
//        ];

//        $modulesDefault = [
//            [
//                'module' => 'layouts'
//            ]
//        ];
//        $modules = array_merge($modules, $modulesDefault);

//        $modules = [
//            'layouts' => ''
//        ];


        $skipModules = [
            'custom_fields',
            'spacer',
            'background',
            'embed',
            'empty',
            'title',
            'text',
            'picture',
            'comments',
            'shop/cart_add',
        ];

        foreach ($modules as $moduleName => $moduleNamespace) {

//            if ($moduleName != 'layouts') {
//                continue;
//            }
            if (in_array($moduleName, $skipModules)) {
                continue;
            }
            Log::info('Processing module: ' . $moduleName);

            $layouts = module_templates($moduleName, $this->template_name);

            if (empty($layouts)) {
                continue;
            }

            foreach ($layouts as $layout) {


                Log::info('Processing module: ' . $moduleName . ' layout: ' . $layout['layout_file']);

                $this->browse(function (Browser $browser) use ($modules, $layouts, $moduleName, $layout) {

                    if (!isset($layout['screenshot_path_for_update_screenshot'])) {
                        return;
                    }
                    $screenshotFile = $layout['screenshot_path_for_update_screenshot'];
                    /*

                                        $screenshotFile = $layout['filename'];
                                        $screenshotFile = str_replace('.blade.php', '.png', $screenshotFile);

                                        $screenshotFileNew = 'Templates/' . $this->template_name . '/resources/assets/img/screenshots/modules/' . $moduleName . '/templates/';
                                        $layoutFileExplode = explode('/', $layout['layout_file']);
                                        if (isset($layoutFileExplode[1])) {
                                            $screenshotFileNew .= $layoutFileExplode[0] . '/' . $layoutFileExplode[1] . '.png';
                                        } else {
                                            $screenshotFileNew .= $layout['layout_file'] . '.png';
                                        }*/

                    $layout['screenshot_file'] = $screenshotFile;
                    $screenshotFileNew = str_replace('.png', '.new.png', $screenshotFile);
                    $layoutName = $layout['layout_file'];
                    $layoutName = str_replace('.php', '', $layoutName);

//                    if (str_contains($layout['filename'], 'header') !== false) {
//                        $browser->resize(900, 600);
//                    } else if (strpos($layout['filename'], 'contact_form') !== false) {
//                        $browser->resize(480, 1200);
//                    } else {
//                        $browser->resize(1360, 800);
//                    }


                    // dump('/preview-skin?module='.$module['module'].'&skin=' . $layoutName . '&no_editmode=1');

                    dump('template/preview-layout?module=' . $moduleName . '&skin=' . $layoutName);

                    $browser->visit('/template/preview-layout?module=' . $moduleName . '&skin=' . $layoutName);

                    Log::info('Visiting: /template/preview-layout?module=' . $moduleName . '&skin=' . $layoutName);
                    $browser->waitFor('#preview-skin-file', 30);
                    $browser->pause(1000);

                    if ($browser->element('#preview-skin-file .module')) {
                        $previewLayoutContentElement = $browser->element('#preview-skin-file .module');

                    } else if ($browser->element('#preview-skin-file .module')) {
                        $previewLayoutContentElement = $browser->element('#preview-skin-file .element');

                    } else {
                        $previewLayoutContentElement = $browser->element('#preview-skin-file');

                    }

                    //check if element has height more than 0 and width more than 0
                    $elementSize = $previewLayoutContentElement->getSize();

                    if ($elementSize->getHeight() == 0 || $elementSize->getWidth() == 0) {
                        Log::info('Skipping screenshot for module: ' . $moduleName . ' layout: ' . $layoutName . ' because element has height or width 0');
                        return;
                    }


                    $previewLayoutContentElement->takeElementScreenshot($screenshotFileNew);

                    if (is_file($screenshotFile)) {
                        $imageComparator = new ImageComparator();
                        $similarity = $imageComparator->compare($screenshotFile, $screenshotFileNew);
                        if (is_numeric($similarity) and $similarity < 95) {
                            rename($screenshotFileNew, $screenshotFile);
                        } else {
                            unlink($screenshotFileNew);
                        }

                    } else {
                        rename($screenshotFileNew, $screenshotFile);
                    }
//
//                    try {
//
//                        $src = $layout['screenshot_file'];
//                        $tn = new \Modules\Media\Support\Thumbnailer($src);
//                        $thumbOptions = array('height' => 640, 'width' => 480);
//                        //$thumbOptions = array('height' => 240, 'width' => 320);
//                        // $thumbOptions['crop'] = true;
//                        $tn->createThumb($thumbOptions, $layout['screenshot_file']);
//
//
//                    } catch (\Exception $e) {
//
//                    }
                });
            }
        }


    }

    #[Test]
public function it_creates_screenshots(): void
    {
        $this->bootTemplate();
        $layouts = module_templates('layouts');
        return;

        foreach ($layouts as $layout) {

//            if (!isset($layout['screenshot_file'])) {
//                continue;
//            }
            if (!isset($layout['screenshot_path_for_update_screenshot'])) {
                continue;
            }
            $layoutName = $layout['layout_file'];
            $layoutName = str_replace('.php', '', $layoutName);

//                $skip = true;
//                if (strpos($layoutName, 'testimonials') !== false) {
//                    $skip = false;
//                }
//                if ($skip) {
//                    continue;
//                }
            $this->browse(function (Browser $browser) use ($layouts, $layout, $layoutName) {
                $screenshotFile = $layout['screenshot_path_for_update_screenshot'];
                $screenshotFileNew = str_replace('.png', '.new.png', $screenshotFile);
                if (strpos($layoutName, 'header') !== false) {
                    $browser->resize(1360, 600);
                }
                if (strpos($layoutName, 'pictures') !== false) {
                    $browser->resize(1360, 1100);
                }
                if (strpos($layoutName, 'footer') !== false) {
                    $browser->resize(1360, 600);
                }
                if (strpos($layoutName, 'animated') !== false) {
                    $browser->resize(1360, 600);
                }
                if (strpos($layoutName, 'content') !== false) {
                    $browser->resize(1360, 960);
                }
                if (strpos($layoutName, 'contacts') !== false) {
                    $browser->resize(1360, 1500);
                }
                Log::info('Visiting: template/preview-layout?module=layouts&skin=' . $layoutName);
                try {
                    $browser->visit('template/preview-layout?module=layouts&skin=' . $layoutName);
                    $browser->waitFor('#preview-skin-file');

                    if (strpos($layoutName, 'contacts') !== false) {
                        $browser->pause(1000);
                    }


                    try {
                        $previewLayoutContentElement = $browser->element('#preview-skin-file');
                        // $previewLayoutContentElement->takeElementScreenshot($layout['screenshot_file']);


                        //check if element has height more than 0 and width more than 0
                        $elementSize = $previewLayoutContentElement->getSize();
                        if ($elementSize->getHeight() == 0 || $elementSize->getWidth() == 0) {
                            Log::info('Skipping screenshot for module: ' . ' layout: ' . $layoutName . ' because element has height or width 0');
                            return;
                        }


                        $previewLayoutContentElement->takeElementScreenshot($screenshotFileNew);
                        if (is_file($screenshotFile)) {
                            $imageComparator = new ImageComparator();
                            $similarity = $imageComparator->compare($screenshotFile, $screenshotFileNew);
                            if (is_numeric($similarity) and $similarity < 95) {
                                rename($screenshotFileNew, $screenshotFile);
                            } else {
                                unlink($screenshotFileNew);
                            }

                        } else {
                            rename($screenshotFileNew, $screenshotFile);
                        }
                    } catch (\Exception $e) {
                        //dump($e->getMessage());
                    }

                } catch (\Exception $e) {
                    //dump($e->getMessage());
                }
            });
        }


    }

}
