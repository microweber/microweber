<?php

namespace MicroweberPackages\LaravelModules\Activators\Concerns;

use Nwidart\Modules\Module;

/**
 * Shared default-activation logic for the file and database activators.
 *
 * A module that is absent from the activator's status store (statuses file /
 * status table) defaults to its manifest "active" flag, which defaults to TRUE.
 * This is what makes Microweber ship with all bundled modules enabled: a newly
 * added module is ON by default unless it opts out (module.json "active": false)
 * or is explicitly disabled in the status store.
 *
 * Using classes must expose `$this->config` (a config repository) and
 * `$this->configPrefix` (e.g. "modules"). The static cache is per using-class,
 * so the file and database activators cache independently.
 */
trait ResolvesModuleDefaultStatus
{
    private static array $defaultActiveStatusCache = [];

    protected function defaultActiveStatus(Module|string $module, ?string $name = null): bool
    {
        $name = $name ?? ($module instanceof Module ? $module->getName() : (string) $module);

        if (array_key_exists($name, self::$defaultActiveStatusCache)) {
            return self::$defaultActiveStatusCache[$name];
        }

        $active = true;

        try {
            if ($module instanceof Module) {
                $active = (bool) $module->json()->get('active', 1);
            } else {
                $path = $this->config->get($this->configPrefix . '.paths.modules');
                if ($path) {
                    $manifest = rtrim((string) $path, '/\\') . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'module.json';
                    if (is_file($manifest)) {
                        $json = json_decode((string) file_get_contents($manifest), true);
                        if (is_array($json) && array_key_exists('active', $json)) {
                            $active = (bool) $json['active'];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            $active = true;
        }

        return self::$defaultActiveStatusCache[$name] = $active;
    }
}
