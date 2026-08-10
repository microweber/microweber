<?php
namespace MicroweberPackages\Database\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Database\Capsule\Manager as Capsule;
use Tests\TestCase;
use MicroweberPackages\Database\Facades\DatabaseManager;

class DatabaseManagerTest extends TestCase
{
    #[Test]
    public function it_build_table(): void {
        DatabaseManager::build_table('peoples', [
            'firstName' => 'text',
            'secondName' => 'text',
            'lastName' => 'text',
            'updated_at' => 'dateTime',
            'created_at' => 'dateTime',
        ]);

        $isTable = DatabaseManager::table_exists('peoples');
        $this->assertTrue($isTable);

    }

    #[Test]

    public function it_build_tablse(): void {
        DatabaseManager::build_tables([
            'posts'=>[
                'name'=>'string',
                'slug'=>'string',
                'category_id'=>'integer',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
            ],
            'categories'=>[
                'name'=>'string',
                'slug'=>'string',
                'description'=>'text',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
            ],
        ]);
        $isTable = DatabaseManager::table_exists('posts');
        $this->assertTrue($isTable);

        $isTable = DatabaseManager::table_exists('categories');
        $this->assertTrue($isTable);
    }

    #[Test]

    public function it_insert_table(): void {
        $this->it_build_table();

        $insert = array();
        $insert['firstName'] = 'Bozhidar';
        $insert['secondName'] = 'Veselinov';
        $insert['lastName'] = 'Slaveykov';

        db_save('peoples', $insert);

        $getPeople = db_get('peoples', 'single=1&firstName=Bozhidar');

        $this->assertEquals('Bozhidar', $getPeople['firstName']);
        $this->assertEquals('Veselinov', $getPeople['secondName']);
        $this->assertEquals('Slaveykov', $getPeople['lastName']);

    }

    #[Test]

    public function it_delete_table_data(): void {
        $this->it_build_table();

        $insert = array();
        $insert['firstName'] = 'Peter';
        $insert['secondName'] = 'Weber';
        $insert['lastName'] = 'Ivanov';

        db_save('peoples', $insert);

        $getPeople = db_get('peoples', 'single=1&firstName=Peter');

        $delete = db_delete('peoples', $getPeople['id']);

        $this->assertTrue(is_numeric($delete));
        $this->assertNotEmpty($delete);

    }

}