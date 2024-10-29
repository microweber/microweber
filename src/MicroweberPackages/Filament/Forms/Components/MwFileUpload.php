<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;

class MwFileUpload extends Field
{
    use HasPlaceholder;

    protected string $view = 'filament-forms::components.mw-file-upload';

    protected array $fileTypes = [];

    protected bool | Closure $isMultiple = false;

//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->afterStateHydrated(static function (MwFileUpload $component, string|array|null $state): void {
//            if (blank($state)) {
//                $component->state([]);
//                return;
//            }
//        });
//    }

    public function fileTypes(array | Closure $fileTypes): static
    {
        $this->fileTypes = $fileTypes;

        return $this;
    }

    public function getFileTypes(): array
    {
        return (array) $this->evaluate($this->fileTypes);
    }

    public function multiple(bool | Closure $condition = true): static
    {
        $this->view = 'filament-forms::components.mw-file-upload-multiple';

        $this->isMultiple = $condition;

        return $this;
    }

    public function isMultiple(): bool
    {
        return (bool) $this->evaluate($this->isMultiple);
    }


}
