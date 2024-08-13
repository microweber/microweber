<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;

class MwFileUpload extends Field
{
    use HasPlaceholder;

    protected string $view = 'filament-forms::components.mw-file-upload';

    protected string $fileTypes = 'file';

    protected bool | Closure $isMultiple = false;

    public function fileTypes(string $fileTypes): static
    {
        $this->fileTypes = $fileTypes;

        return $this;
    }

    public function getFileTypes(): string
    {
        return $this->fileTypes;
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
