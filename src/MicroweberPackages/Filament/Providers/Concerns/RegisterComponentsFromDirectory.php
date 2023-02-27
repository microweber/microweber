<?php

namespace MicroweberPackages\Filament\Providers\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

trait RegisterComponentsFromDirectory

{
    public function registerResourcesFromDirectory(string $baseClass, ?string $directory, ?string $namespace): void
    {
        $this->registerComponentsFromDirectory(
            $baseClass,
            $this->resources,
            $directory,
            $namespace,
        );
    }

    protected function registerComponentsFromDirectory(string $baseClass, array &$register, ?string $directory, ?string $namespace): void
    {
        if (blank($directory) || blank($namespace)) {
            return;
        }


        $filesystem = app(Filesystem::class);

        if ((!$filesystem->exists($directory)) && (!Str::of($directory)->contains('*'))) {
            return;
        }

        $namespace = Str::of($namespace);

        $register = array_merge(
            $register,
            collect($filesystem->allFiles($directory))
                ->map(function (SplFileInfo $file) use ($namespace): string {
                    $variableNamespace = $namespace->contains('*') ? str_ireplace(
                        ['\\' . $namespace->before('*'), $namespace->after('*')],
                        ['', ''],
                        Str::of($file->getPath())
                            ->after(base_path())
                            ->replace(['/'], ['\\']),
                    ) : null;

                    if (is_string($variableNamespace)) {
                        $variableNamespace = (string)Str::of($variableNamespace)->before('\\');
                    }
                    $variableNamespace = null;
                    return (string)$namespace
                        ->append('\\', $file->getRelativePathname())
                        ->replace('*', $variableNamespace)
                        ->replace(['/', '.php'], ['\\', '']);
                })
                ->filter(function (string $class) use ($baseClass) {
                    $isSubclass = is_subclass_of($class, $baseClass);
                    $isNotAbstract = 1;
                    dump($baseClass,$class,$isSubclass);
                 //   $isNotAbstract = !(new \ReflectionClass($class))->isAbstract();
                    return $isSubclass && $isNotAbstract;
                })
                ->all(),
        );


    }
}
