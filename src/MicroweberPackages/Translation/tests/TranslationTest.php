<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 3/8/2021
 * Time: 3:28 PM
 */

namespace MicroweberPackages\Translation\tests;


use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Translation\TranslationImport;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;

class TranslationTest extends TestCase
{

    public function testImportLanguage() {

        $installLanguage = 'bg_BG';

        $installResponse = TranslationPackageInstallHelper::installLanguage($installLanguage);
        $this->assertNotEmpty($installResponse);
        $this->assertArrayHasKey('success', $installResponse);

        mw()->lang_helper->set_current_lang('bg_BG');

        $this->assertEquals('Табло', _e('Dashboard', 'true'));
        $this->assertEquals('Настройки', _e('Settings', 'true'));
        $this->assertEquals('Магазин', _e('Shop', 'true'));
        $this->assertEquals('Модули', _e('Modules', 'true'));
        $this->assertEquals('Коментари', _e('Comments', 'true'));


        mw()->lang_helper->set_current_lang('en_US');

        $this->assertEquals('Dashboard', _e('Dashboard', 'true'));
        $this->assertEquals('Settings', _e('Settings', 'true'));
        $this->assertEquals('Shop', _e('Shop', 'true'));
        $this->assertEquals('Modules', _e('Modules', 'true'));
        $this->assertEquals('Comments', _e('Comments', 'true'));

    }

    public function testAddNewTranslationsToLocale()
    {

        $newLocale = 'bg_BG';

        $newTranslations = [];

        $newTranslations[] = [
          'translation_namespace' =>'*',
          'translation_group' =>'*',
          'translation_key' =>'How are you?',
          'translation_text' =>'Как си?',
          'translation_locale' =>$newLocale,
        ];

        $newTranslations[] = [
          'translation_namespace' =>'*',
          'translation_group' =>'*',
          'translation_key' =>'Are you okay?',
          'translation_text' =>'Всичко точно ли е?',
          'translation_locale' =>$newLocale,
        ];

        $import = new TranslationImport();
        $importResponse = $import->import($newTranslations);

        $this->assertNotEmpty($importResponse);
        $this->assertArrayHasKey('success', $importResponse);

        mw()->lang_helper->set_current_lang($newLocale);

        $this->assertEquals('Как си?', _e('How are you?', 'true'));
        $this->assertEquals('Всичко точно ли е?', _e('Are you okay?', 'true'));


        mw()->lang_helper->set_current_lang('en_US');

        $this->assertEquals('How are you?', _e('How are you?', 'true'));
        $this->assertEquals('Are you okay?', _e('Are you okay?', 'true'));

    }

    public function testImportAllLanguages() {

        $availableTranslations = TranslationPackageInstallHelper::getAvailableTranslations();

        $this->assertNotEmpty($availableTranslations);

        foreach($availableTranslations as $availableLocale=>$availableLanguage) {
            $installResponse = TranslationPackageInstallHelper::installLanguage($availableLocale);
            $this->assertNotEmpty($installResponse);
            $this->assertArrayHasKey('success', $installResponse);
        }


    }
}