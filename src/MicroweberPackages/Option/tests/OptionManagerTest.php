<?php
namespace MicroweberPackages\Option\tests;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Core\tests\TestCase;

class OptionManagerTest extends TestCase
{
    #[Test]
    public function it_option(): void {
        $option = array();
        $option['option_value'] = '6 tochki';
        $option['option_key'] = 'BobiBobi';
        $option['option_group'] = 'kak_go_pravish_taka';

        $save = save_option($option);

        $this->assertNotEmpty($save);

        $get = get_option('BobiBobi', 'kak_go_pravish_taka');

        $this->assertEquals('6 tochki', $get);

    }

}