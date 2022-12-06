<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 3/8/2021
 * Time: 3:28 PM
 */

namespace MicroweberPackages\Translation\tests;


use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationImport;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;

class TranslationTest extends TestCase
{

    public function testImportLanguage()
    {

        // Truncate translation texts
        TranslationKey::truncate();
        TranslationText::truncate();

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
            'translation_namespace' => '*',
            'translation_group' => '*',
            'translation_key' => 'How are you?',
            'translation_text' => 'Как си?',
            'translation_locale' => $newLocale,
        ];

        $newTranslations[] = [
            'translation_namespace' => '*',
            'translation_group' => '*',
            'translation_key' => 'Are you okay?',
            'translation_text' => 'Добре ли си?',
            'translation_locale' => $newLocale,
        ];

        // Try to add the same text and key
        $newTranslations[] = [
            'translation_namespace' => '*',
            'translation_group' => '*',
            'translation_key' => 'Are you okay?',
            'translation_text' => 'Добре ли си?-презаписано',
            'translation_locale' => $newLocale,
        ]; // This must be not broke the importing and dublicating on translation_texts table


        // Try to overwrite existing translation
        $newTranslations[] = [
            'translation_namespace' => '*',
            'translation_group' => '*',
            'translation_key' => 'Comments',
            'translation_text' => 'Клюки',
            'translation_locale' => $newLocale,
        ];

        $import = new TranslationImport();
        $import->replaceTexts(true);

        $importResponse = $import->import($newTranslations);

        $this->assertNotEmpty($importResponse);
        $this->assertArrayHasKey('success', $importResponse);

        mw()->lang_helper->set_current_lang($newLocale);


        $this->assertEquals('Как си?', _e('How are you?', 'true'));
        $this->assertEquals('Добре ли си?-презаписано', _e('Are you okay?', 'true'));
        $this->assertEquals('Клюки', _e('Comments', 'true'));


        mw()->lang_helper->set_current_lang('en_US');

        $this->assertEquals('How are you?', _e('How are you?', 'true'));
        $this->assertEquals('Are you okay?', _e('Are you okay?', 'true'));
        $this->assertEquals('Comments', _e('Comments', 'true'));

    }

    public function testImportSomeLanguages()
    {

        $availableTranslations = TranslationPackageInstallHelper::getAvailableTranslations();

        $this->assertNotEmpty($availableTranslations);

        $i = 0;
        foreach ($availableTranslations as $availableLocale => $availableLanguage) {
            $i++;
            if ($i > 3) {
                continue;
            }
            $installResponse = TranslationPackageInstallHelper::installLanguage($availableLocale);
            $this->assertNotEmpty($installResponse);
            $this->assertArrayHasKey('success', $installResponse);
        }

    }

    public function testTheLangFunctionsTranslate()
    {

        $translate1 =  __('testTheLangFunctionsTranslate1 - '.uniqid());
        $translate2 = _e('testTheLangFunctionsTranslate1 - '.uniqid());


        $getNewKeys = app()->translator->getNewKeys();
        dump($getNewKeys);



    }
}
