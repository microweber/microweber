<?php

namespace MicroweberPackages\Module\Activators;


use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Log\LogManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Foundation\Application;
use Nwidart\Modules\Contracts\ActivatorInterface;
use Nwidart\Modules\Module;

class DatabaseActivator implements ActivatorInterface
{

    /**
     * Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Laravel cache instance
     *
     * @var CacheManager
     */
    private $cache;

    /**
     * Laravel Database connection
     *
     * @var \Illuminate\Database\Connection
     */
    private $connection;

    /**
     * Laravel Database modules table
     *
     * @var string
     */
    private $table;

    /**
     * Laravel config instance
     *
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var string
     */
    private $cacheLifetime;

    /**
     * Array of modules activation statuses
     *
     * @var array
     */
    private $modulesStatuses;

    /**
     * Application Logger
     *
     * @var LogManager
     */
    private $logger;

    public function __construct (Container $app)
    {


        $this->app = $app;
        $this->cache = $app ['cache'];
        $this->connection = $app ['db.connection'];
        $this->config = $app ['config'];
        $this->table = $this->config ('table');
        $this->cacheKey = $this->config ('cache-key');
        $this->cacheLifetime = $this->config ('cache-lifetime');
        $this->logger = $app ['log'];
        $this->modulesStatuses = $this->getModulesStatuses ();


    }

    /**
     * Get the path of the file where statuses are stored
     *
     * @return string
     */
    public function getTableName (): string
    {
        if (Schema::hasTable ($this->table))
        {
            return $this->table;
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function reset (): void
    {
        if (! empty ($this->getTableName ()))
        {
            $this->connection->table ($this->getTableName ())->where ('type', 1)->update (['status' => 0]);
        }
        $this->modulesStatuses = [];
        $this->flushCache ();
    }

    /**
     * @inheritDoc
     */
    public function enable (Module $module): void
    {
        $this->setActive ($module, true);
    }

    /**
     * @inheritDoc
     */
    public function disable (Module $module): void
    {
        $this->setActive ($module, false);
    }

    /**
     * @inheritDoc
     */
    public function hasStatus (Module $module, bool $status): bool
    {
        if (!isset($this->modulesStatuses [$module->getName ()])) {
            return $status === false;
        }

        return $this->modulesStatuses [$module->getName ()] === $status;
    }

    /**
     * @inheritDoc
     */
    public function setActive (Module $module, bool $active): void
    {
        $this->setActiveByName ($module->getName (), $active);
        $this->syncModule ([$module]);
    }

    /**
     * @inheritDoc
     */
    public function setActiveByName (string $name, bool $status): void
    {
        $this->modulesStatuses [$name] = $status;
        $this->writeStatus ();
        $this->flushCache ();
    }

    /**
     * @inheritDoc
     */
    public function delete (Module $module): void
    {
        if (!isset ($this->modulesStatuses [$module->getName ()])) {
            return;
        }
        unset ($this->modulesStatuses [$module->getName ()]);
        $this->syncModule ([$module]);
        $this->writeStatus ();
        $this->flushCache ();
    }

    /**
     * insert module info into database
     * @param array $modules
     */
    private function syncModule (array $modules) {
        try {
            $this->connection->transaction (function () use ($modules) {

                foreach ($modules as $key => $module)
                {
                    if ($module instanceof Module)
                    {
                        $module = array_merge(
                            $module->json ()->getAttributes (),
                            ['version' => $module->get ('version')],
                            ['type' => $module->get ('type', 1)],
                            ['path' => $module->getPath ()]
                        );
                    }
                    $this->connection
                        ->table ($this->getTableName ())
                        ->updateOrInsert (
                            ['name' => Arr::get ($module, 'name')],
                            [
                                'alias' => Arr::get ($module, 'alias', ''),
                                'description' => Arr::get ($module, 'description', ''),
                                'path' => Arr::get ($module, 'path', ''),
                                'version' => Arr::get ($module, 'version', 'dev'),
                                'type' => Arr::get ($module, 'type', '1'),
                                'priority' => Arr::get ($module, 'priority', '1024'),
                                'sort' => Arr::get ($module, 'order', '0'),
                                'status' => 1
                            ]
                        );
                }
            }, 3);
        } catch (\Throwable $e) {
            $this->logger->error ($e->getMessage ());
        }
    }

    /**
     * Writes the activation statuses in a file, as json
     */
    private function writeStatus (): void
    {
        if (! empty ($this->getTableName ()))
        {
            try {
                $this->connection->transaction (function () {
                    $this->connection
                        ->table ($this->getTableName ())
                        ->where ('type', 1)
                        ->update (['status' => 0]);
                    foreach ($this->modulesStatuses as $name => $status) {
                        if ($status === 1)
                        {
                            $this->connection
                                ->table ($this->getTableName ())
                                ->where ('name', $name)
                                ->where ('type', 1)
                                ->update (['status' => $status]);
                        }
                    }
                }, 3);
            } catch (\Throwable $e) {
                $this->logger->error ($e->getMessage ());
            }
        }
    }

    /**
     * Reads the json file that contains the activation statuses.
     * @return array
     */
    private function readStatus (): array
    {
        $statues = [];

        $allModules = app()->module_repository->getAllModules();
        $all_enabled_modules = $this->app ['modules']->scanJson ();
      //  dd(343444444444443,$all_enabled_modules);

//        if (! empty ($this->getTableName ()))
//        {
//            $statues = $this->connection
//                ->table ($this->getTableName ())
//                ->get ()
//                ->reduce (function ($reduce, $item) {
//                    $reduce [$item->name] = ($item->status === 1);
//                    return $reduce;
//                }, []);
//
//            if (empty ($statues))
//            {
//                $all_enabled_modules = $this->app ['modules']->scanJson ();
//
//                $this->syncModule ($all_enabled_modules);
//
//                if (count ($all_enabled_modules) > 0)
//                {
//                    return $this->readStatus ();
//                }
//            }
//        }
        return $statues;
    }


    private function scanJson ()
    {
        $paths = $this->getScanPaths ();

        $modules = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->getFiles()->glob ("{$path}/module.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                try {
                    $module = Json::make ($manifest)->getAttributes ();
                    $module ['path'] = dirname ($manifest);
                    $modules [] = $module;
                } catch (\Exception $e) {

                }
            }
        }

        return $modules;
    }

    /**
     * Get modules statuses, either from the cache or from
     * the json statuses file if the cache is disabled.
     * @return array
     */
    private function getModulesStatuses (): array
    {
        if (!$this->config->get ('modules.cache.enabled')) {
            return $this->readStatus ();
        }

        return $this->cache->remember ($this->cacheKey, $this->cacheLifetime, function () {
            return $this->readStatus ();
        });
    }

    /**
     * Reads a config parameter under the 'activators.file' key
     *
     * @param  string $key
     * @param  $default
     * @return mixed
     */
    private function config (string $key, $default = null)
    {
        $activator = $this->config->get ('modules.activator', 'database');
        return $this->config->get ("modules.activators.$activator." . $key, $default);
    }

    /**
     * Flushes the modules activation statuses cache
     */
    private function flushCache (): void
    {
        $this->cache->forget ($this->cacheKey);
    }
}
