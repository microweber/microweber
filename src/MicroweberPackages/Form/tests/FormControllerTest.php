<?php

namespace MicroweberPackages\Form\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;


class FormControllerTest extends TestCase
{
    public function testFormPostController()
    {

        $rel = 'module';
        $rel_id = 'layouts-testCustomContactFormSettingsRequiredSubmit' . rand(1111, 9999) . '-contact-form';
        $fields_csv_str = 'name[type=text,field_size=6,show_placeholder=true,required=true],';
        $fields_csv_str .= 'email[type=email,field_size=6,show_placeholder=true,required=true],';
        $fields_csv_str .= 'message[type=textarea,field_size=12,show_placeholder=true,required=true]';

        $fields = mw()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);
        // Disable captcha
        save_option(array(
            'option_group' => $rel_id,
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $fields = mw()->fields_manager->get(['rel_type' => $rel, 'rel_id' => $rel_id]);
        $this->assertTrue(!empty($fields));

        $response = $this->call('POST', route('api.post.form'), [
            'rel_id' => $rel_id,
            'rel' => $rel,
            'name' => 'John Doe',
            'email' => 'example@email.com',
            'message' => 'Hello World']);


        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $this->assertEquals(json_decode($content, true)['success'], 'Your message has been sent');

    }

    public function testFormPostControllerWithoutRelId()
    {
        $response = $this->call('POST', route('api.post.form'), [
            'name' => 'John Doe',
            'email' => 'example@email.com',
            'message' => 'Hello World']);


        $this->assertEquals(422, $response->status());


    }

}
