<?php

namespace MicroweberPackages\Microweber\Traits;

//@todo move to class
trait ManagesContent
{
    /**
     * Retrieves content by its ID.
     *
     * @param int $id The ID of the content.
     * @return array The content data.
     */
    public function contentGetById($id)
    {
        return app()->content_manager->get_by_id($id);
    }

    /**
     * Retrieves content based on the provided parameters.
     *
     * @param array $params The parameters to filter the content.
     * @return array The content data.
     */
    public function contentGet($params)
    {
        return app()->content_manager->get($params);
    }

    /**
     * Retrieves content by its URL.
     *
     * @param string $url The URL of the content.
     * @return array The content data.
     */
    public function contentGetByURL($url)
    {
        return app()->content_manager->get_by_url($url);
    }

    /**
     * Retrieves content by its title.
     *
     * @param string $title The title of the content.
     * @return array The content data.
     */
    public function contentGetByTitle($title)
    {
        return app()->content_manager->get_by_title($title);
    }

    /**
     * Saves content data.
     *
     * @param array $data The data of the content to save.
     * @return array The saved content data.
     */
    public function contentSave($data)
    {
        return app()->content_manager->save_content($data);
    }

    /**
     * Unpublishes content by its ID.
     *
     * @param int $id The ID of the content to unpublish.
     * @return int $id The ID of the content that was unpublished.
     */
    public function contentUnpublish($id)
    {
        return app()->content_manager->set_unpublished($id);
    }

    /**
     * Publishes content by its ID.
     *
     * @param int $id The ID of the content to publish.
     * @return $id The ID of the content that was published.
     */
    public function contentPublish($id)
    {
        return app()->content_manager->set_published($id);
    }
}
