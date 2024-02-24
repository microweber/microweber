<?php

namespace MicroweberPackages\Microweber\Traits;

trait ManagesContent
{
    public function contentGetById($id)
    {
        return app()->content_manager->get_by_id($id);
    }

    public function contentGet($params)
    {
        return app()->content_manager->get($params);
    }

    public function contentGetByURL($url)
    {
        return app()->content_manager->get_by_url($url);
    }

    public function contentGetByTitle($title)
    {
        return app()->content_manager->get_by_title($title);
    }

    public function contentSave($data)
    {
        return app()->content_manager->save_content($data);
    }

    public function contentUnpublish($id)
    {
        return app()->content_manager->set_unpublished($id);
    }

    public function contentPublish($id)
    {
        return app()->content_manager->set_published($id);
    }
}
