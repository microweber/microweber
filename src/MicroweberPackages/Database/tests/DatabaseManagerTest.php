<?php
namespace MicroweberPackages\Database\tests;

use Illuminate\Database\Capsule\Manager as Capsule;
use MicroweberPackages\Core\tests\TestCase;

class DatabaseManagerTest extends TestCase
{
    public function testBuildTable()
    {
        app()->database_manager->build_table('peoples', [
            'firstName' => 'text',
            'secondName' => 'text',
            'lastName' => 'text',
            'updated_at' => 'dateTime',
            'created_at' => 'dateTime',
        ]);

        $isTable = app()->database_manager->table_exists('peoples');
        $this->assertTrue($isTable);

    }

    public function testBuildTablse()
    {
        app()->database_manager->build_tables([
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
        $isTable = app()->database_manager->table_exists('posts');
        $this->assertTrue($isTable);

        $isTable = app()->database_manager->table_exists('categories');
        $this->assertTrue($isTable);
    }

    public function testInsertTable()
    {
        $this->testBuildTable();

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

    public function testDeleteTableData()
    {
        $this->testBuildTable();

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