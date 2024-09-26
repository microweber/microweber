<?php

namespace MicroweberPackages\MetaTags\tests;

class TemplateMetaTagsFunctionsTest extends \MicroweberPackages\Core\tests\TestCase
{


    function testTemplateHeadMeta()
    {
        $id = uniqid('mw-meta-tags-test-inserted-from-template_head_as_string_test_function');
        meta_tags_head_add('<link rel="unit-test"  id="'.$id.'" type="unit-test">');
        meta_tags_head_add(function () use ($id) {
            $link = '<link rel="unit-test"  id="callback-'.$id.'" type="unit-test">';
            return $link;
        });
        $template_head_meta = meta_tags_head();
        $this->assertIsString($template_head_meta);
        $this->assertStringContainsString('unit-test', $template_head_meta);
        $this->assertStringContainsString($id, $template_head_meta);
        $this->assertStringContainsString('callback-'.$id, $template_head_meta);
     }
     function testTemplateFooterMeta()
    {
        $id = uniqid('mw-meta-tags-test-inserted-from-template_footer_as_string_test_function');
        meta_tags_footer_add('<link rel="unit-test"  id="'.$id.'" type="unit-test">');
        meta_tags_footer_add(function () use ($id) {
            $link = '<link rel="unit-test"  id="callback-'.$id.'" type="unit-test">';
            return $link;
        });
        $template_head_meta = meta_tags_footer();
        $this->assertIsString($template_head_meta);
        $this->assertStringContainsString('unit-test', $template_head_meta);
        $this->assertStringContainsString($id, $template_head_meta);
        $this->assertStringContainsString('callback-'.$id, $template_head_meta);
     }
}
