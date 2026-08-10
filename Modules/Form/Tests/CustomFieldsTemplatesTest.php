<?php

namespace Modules\Form\Tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use MicroweberPackages\Database\Facades\DatabaseManager;

class CustomFieldsTemplatesTest extends TestCase
{
    public $template_name = 'default';

    public function setUp() : void
    {
        if (!defined('TEMPLATE_NAME')) {
            define('TEMPLATE_NAME', $this->template_name);
        }

        parent::setUp();
        app()->content_manager->define_constants(['active_site_template' => $this->template_name]);

        save_option('current_template', $this->template_name,'template');

        // set permission to save custom fields (normally available to admin users)
        DatabaseManager::extended_save_set_permission(true);
    }

    #[Test]

    public function it_custom_template(): void {

        app()->content_manager->define_constants(['active_site_template' => $this->template_name]);

        save_option('current_template', $this->template_name,'template');

        // Make new custom template
        $templateCustomFields = app()->template_manager->dir()
                    . 'modules' . DS
                    . 'custom_fields' .DS
                    . 'templates' . DS
                    . 'unit-test';
        mkdir_recursive($templateCustomFields);

        $templateCustomFieldsIndex = '<?php
        /*
         *
         * type: layout
         *
         * name: Unit Test
         *
         * description: Unit Test
         *
         */
        ?>
        <?php if (!empty($fields_group)): ?>
            <?php foreach ($fields_group as $fields): ?>

                <?php if (!empty($fields)): ?>

                    <?php foreach ($fields as $field): ?>
                        <?php echo $field[\'html\']; ?>
                    <?php endforeach; ?>

                <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>
        ';

        file_put_contents($templateCustomFields . DS . 'index.php', $templateCustomFieldsIndex);
        file_put_contents($templateCustomFields . DS . 'text.php', '<input type="text" class="unit-test" />');

        $rel = 'module';
        $rel_id = 'layouts-' . rand(1111, 9999) . '-contact-form';
        $fields_csv_str = 'text, email';
        $fields_csv_array = explode(',', $fields_csv_str);

        $fields = app()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);
        foreach ($fields as $key => $field_id) {

            $option = array();
            $option['option_value'] = 'mw-ui';
            $option['option_key'] = 'template';
            $option['option_group'] = $field_id;
            $save = save_option($option);

            $output = app()->fields_manager->make($field_id);
            $field = app()->fields_manager->getById($field_id);

            if ($field['type'] == 'text') {
                $checkInputClass = false;
                if (strpos($output, 'mw-ui-field') !== false) {
                    $checkInputClass = true;
                }
                $this->assertEquals(true, $checkInputClass);
            }

            if ($field['type'] == 'email') {
                $checkInputClass = false;

                if (strpos($output, 'class="mw-ui-field"') !== false) {
                    $checkInputClass = true;
                }
                $this->assertEquals(true, $checkInputClass);
            }

        }

        unlink($templateCustomFields . DS . 'index.php');
        unlink($templateCustomFields . DS . 'text.php');
        rmdir($templateCustomFields);

    }

    #[Test]

    public function it_bootstrap_tempalte(): void {
        $rel = 'module';
        $rel_id = 'layouts-' . rand(1111, 9999) . '-contact-form';
        $fields_csv_str = 'text, dropdown, number, phone, website, email, fileupload, message';
        $fields_csv_array = explode(',', $fields_csv_str);

        $fields = app()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);

        foreach ($fields as $key => $field_id) {

            $option = array();
            $option['option_value'] = 'bootstrap3';
            $option['option_key'] = 'template';
            $option['option_group'] = $field_id;
            $save = save_option($option);

            $output = app()->fields_manager->make($field_id);
            $field = app()->fields_manager->getById($field_id);

            $checkRow = false;
            if (strpos($output, 'class="col-md-12') !== false) {
                $checkRow = true;
            }
            if (!$checkRow) {
               // var_dump($field);
               //var_dump($output);
              // die();
                // echo $field['type'] . PHP_EOL;
            }
            $this->assertEquals(true, $checkRow);

            $checkInputClass = false;
            if (strpos($output, 'class="form-control') !== false) {
                $checkInputClass = true;
            }
            if (!$checkInputClass) {
                //var_dump($output);
                //echo $field['type'] . PHP_EOL;
            }

            $this->assertEquals(true, $checkInputClass);

            $checkFormGroup = false;
            if (strpos($output, 'class="form-group') !== false) {
                $checkFormGroup = true;
            }
            if (!$checkFormGroup) {
                // echo $field['type'] . PHP_EOL;
            }
            $this->assertEquals(true, $checkFormGroup);

        }
    }

    #[Test]

    public function it_bootstrap_new_tempalte(): void {
        $rel = 'module';
        $rel_id = 'layouts-' . rand(1111, 9999) . '-contact-form';
        $fields_csv_str = 'text, dropdown, number, phone, website, email, fileupload, message';
        $fields_csv_array = explode(',', $fields_csv_str);

        $fields = app()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);
        foreach ($fields as $key => $field_id) {

            $option = array();
            $option['option_value'] = 'bootstrap4';
            $option['option_key'] = 'template';
            $option['option_group'] = $field_id;
            $save = save_option($option);

            $output = app()->fields_manager->make($field_id);
            $field = app()->fields_manager->getById($field_id);

            $checkRow = false;
            if (strpos($output, 'class="col-sm-12 col-md-12 col-lg-12"') !== false) {
                $checkRow = true;
            }
            if (!$checkRow) {
                //   echo $field['type'] . PHP_EOL;
            }
            $this->assertEquals(true, $checkRow);

            $checkInputClass = false;
            if (strpos($output, 'class="form-control') !== false) {
                $checkInputClass = true;
            }
            if (!$checkInputClass) {
                //  echo $field['type'] . PHP_EOL;
            }

            $this->assertEquals(true, $checkInputClass);

            $checkFormGroup = false;
            if (strpos($output, 'class="form-group') !== false) {
                $checkFormGroup = true;
            }
            if (!$checkFormGroup) {
                // echo $field['type'] . PHP_EOL;
            }
            $this->assertEquals(true, $checkFormGroup);

        }
    }

    #[Test]

    public function it_mw_ui_tempalte(): void {
        $rel = 'module';
        $rel_id = 'layouts-' . rand(1111, 9999) . '-contact-form';
        $fields_csv_str = 'text, dropdown, number, phone, website, email, fileupload, message';
        $fields_csv_array = explode(',', $fields_csv_str);

        $fields = app()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);
        foreach ($fields as $key => $field_id) {

            $option = array();
            $option['option_value'] = 'mw-ui';
            $option['option_key'] = 'template';
            $option['option_group'] = $field_id;
            $save = save_option($option);

            $output = app()->fields_manager->make($field_id);
            $field = app()->fields_manager->getById($field_id);

            $checkRow = false;
            if (strpos($output, 'class="mw-flex-col-md-12') !== false) {
                $checkRow = true;
            }
            if (!$checkRow) {
                // echo $field['type'] . PHP_EOL;
            }

            $this->assertEquals(true, $checkRow);


            $checkInputClass = false;
            if (strpos($output, 'class="mw-ui-field') !== false) {
                $checkInputClass = true;
            }
            if (!$checkInputClass) {
                //   echo $field['type'] . PHP_EOL;
            }
            $this->assertEquals(true, $checkInputClass);

            $checkFormGroup = false;
            if (strpos($output, 'class="mw-ui-controls') !== false) {
                $checkFormGroup = true;
            }
            if (!$checkFormGroup) {
                // echo $field['type'] . PHP_EOL;
            }

            $this->assertEquals(true, $checkFormGroup);
        }
    }
}
