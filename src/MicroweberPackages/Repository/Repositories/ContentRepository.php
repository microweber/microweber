<?php


namespace MicroweberPackages\Repository\Repositories;


use MicroweberPackages\Content\Content;
use MicroweberPackages\Repository\Repositories\Interfaces\ContentRepositoryInterface;

class ContentRepository implements ContentRepositoryInterface
{
    public function all()
    {
        return Content::all();
    }

    public function getById($id)
    {
        return Content::where('id', $id)->first();
    }
}