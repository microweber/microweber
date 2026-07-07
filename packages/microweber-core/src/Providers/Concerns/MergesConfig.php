<?php

namespace MicroweberPackages\Core\Providers\Concerns;

use Illuminate\Support\Arr;

trait MergesConfig
{
    protected function mergeConfig(array $original, array $merging): array
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            if (!Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            if ($key === 'middleware' || $key === 'register') {
                continue;
            }

            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }

        return $array;
    }

    protected function mergeConfigFrom($path, $key): void
    {
        $config = $this->app['config']->get($key) ?? [];

        $this->app['config']->set($key, $this->mergeConfig(require $path, $config));
    }
}
