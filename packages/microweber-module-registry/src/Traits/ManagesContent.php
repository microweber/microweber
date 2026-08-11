<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

/**
 * Optional CMS content helpers on the registry.
 *
 * Soft-depends on `app()->content_manager`. Safe no-ops / empty results
 * when the CMS content manager is not bound (standalone apps).
 *
 * @deprecated Prefer injecting the content manager directly in CMS code.
 */
trait ManagesContent
{
    public function contentGetById(mixed $id): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'get_by_id')) {
            return null;
        }

        return $manager->get_by_id($id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function contentGet(array $params): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'get')) {
            return null;
        }

        return $manager->get($params);
    }

    public function contentGetByURL(string $url): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'get_by_url')) {
            return null;
        }

        return $manager->get_by_url($url);
    }

    public function contentGetByTitle(string $title): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'get_by_title')) {
            return null;
        }

        return $manager->get_by_title($title);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function contentSave(array $data): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'save_content')) {
            return null;
        }

        return $manager->save_content($data);
    }

    public function contentUnpublish(mixed $id): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'set_unpublished')) {
            return null;
        }

        return $manager->set_unpublished($id);
    }

    public function contentPublish(mixed $id): mixed
    {
        $manager = $this->resolveContentManager();
        if ($manager === null || ! method_exists($manager, 'set_published')) {
            return null;
        }

        return $manager->set_published($id);
    }

    private function resolveContentManager(): ?object
    {
        if (! function_exists('app')) {
            return null;
        }

        try {
            $app = app();
            if (is_object($app) && isset($app->content_manager) && is_object($app->content_manager)) {
                return $app->content_manager;
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }
}
