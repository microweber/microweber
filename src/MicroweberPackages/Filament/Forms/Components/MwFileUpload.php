<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;

class MwFileUpload extends Field
{
    use HasPlaceholder;

    protected string $view = 'mw-filament::components.mw-file-upload';

    protected array $fileTypes = [];

    protected bool|Closure $isMultiple = false;

    // Redesigned uploader: accepted extensions shown to the user, max size, and a
    // compact layout for narrow side-panel fields (e.g. favicon).
    protected array $acceptExtensions = ['png', 'svg', 'jpg', 'jpeg', 'gif', 'webp'];

    protected int $maxSizeMb = 10;

    protected bool $compactMode = false;

    public function acceptExtensions(array $exts): static
    {
        $this->acceptExtensions = array_map('strtolower', $exts);

        return $this;
    }

    public function getAcceptExtensions(): array
    {
        return $this->acceptExtensions;
    }

    public function maxSizeMb(int $mb): static
    {
        $this->maxSizeMb = $mb;

        return $this;
    }

    public function getMaxSizeMb(): int
    {
        return $this->maxSizeMb;
    }

    public function compact(bool $condition = true): static
    {
        $this->compactMode = $condition;

        return $this;
    }

    public function isCompact(): bool
    {
        return $this->compactMode;
    }

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

    public function fileTypes(array|Closure $fileTypes): static
    {
        $this->fileTypes = $fileTypes;

        return $this;
    }

    public function getFileTypes(): array
    {
        return (array)$this->evaluate($this->fileTypes);
    }

    public function image(): static
    {

        $this->fileTypes = ['image/*'];

        return $this;
    }

    public function audio(): static
    {

        $this->fileTypes = ['audio/*'];

        return $this;
    }

    public function video(): static
    {

        $this->fileTypes = ['video/*'];

        return $this;
    }


    public function multiple(bool|Closure $condition = true): static
    {
        $this->view = 'mw-filament::components.mw-file-upload-multiple';

        $this->isMultiple = $condition;

        return $this;
    }

    public function isMultiple(): bool
    {
        return (bool)$this->evaluate($this->isMultiple);
    }


}
