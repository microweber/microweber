<?php

namespace MicroweberPackages\Media\tests;

use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;

use MicroweberPackages\Media\Traits\MediaTrait;

class ContentTestModel extends Model
{
    use MediaTrait;

    protected $table = 'content';

}

class MediaTest extends TestCase
{
    public function asdasdasdtestAddMediaToModel()
    {
        $newPage = new ContentTestModel();
        $newPage->title = 'Pictures from Sofia';

        $newPage->addMedia([
            'filename' => 'http://DESKTOP-COEV57U/./lorempixel.com/400/200/',
            'title' => 'View from Vitosha'
        ]);

//        $newPage->addMedia([
//            'filename' => 'http://lorempixel.com/400/200/',
//            'title' => 'View from Vitosha 2'
//        ]);
//
//        $newPage->addMedia([
//            'filename' => 'http://lorempixel.com/400/200/',
//            'title' => 'View from Vitosha 3'
//        ]);

        $newPage->save();

        dd('DONE');
    }

    public function testDeleteMediaToModel()
    {
        $newPage = new ContentTestModel();

        $newPage->title = 'Pictures from Sofia';
        $newPage->addMedia([
            'filename' => 'http://DESKTOP-COEV57U/./lorempixel.com/400/200/',
            'title' => 'View from Vitosha MUST DELETE!'
        ]);

        $newPage->addMedia([
            'filename' => 'http://DESKTOP-COEV57U/./lorempixel.com/400/200/',
            'title' => 'View from Vitosha 3'
        ]);

        $newPage->addMedia([
            'filename' => 'http://DESKTOP-COEV57U/./lorempixel.com/400/200/',
            'title' => 'View from Vitosha 4'
        ]);

        $newPage->save();
dd($newPage->media);
//dd($newPage->media());
        $mediaToDel = $newPage->media[0];
        //dump($mediaToDel);
        $newPage->deleteMedia($mediaToDel);
        dump('-----------------');
        dump($newPage->media);
    }
}
