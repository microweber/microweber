<?php

namespace Microweber\tests;


class LangTest extends TestCase
{


    public function testLang()
    {

        $current_lang = current_lang();
        set_current_lang('bg');
        $new_current_lang = current_lang();

        $this->assertEquals('en', $current_lang);
        $this->assertEquals('bg', $new_current_lang);

        $lang_string_test = _e("Select country", true);

        $this->assertEquals('Избери държава', $lang_string_test);
    }




}