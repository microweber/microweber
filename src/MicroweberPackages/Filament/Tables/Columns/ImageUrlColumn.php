<?php

namespace MicroweberPackages\Filament\Tables\Columns;

use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\HtmlString;

use Closure;

class ImageUrlColumn extends ImageColumn
{
    protected string | Closure | null $imageUrl = null;

    public int $backgroundCroppedHeight = 100;
    public bool $backgroundCropped = false;

    public function imageUrl(string | Closure | null $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getImageUrl(?string $state = null): ?string
    {
        return $this->evaluate($this->imageUrl);
    }

    public function getView(): string
    {
        if ($this->backgroundCropped) {
            return 'filament-tables::columns.image-column-cropped';
        }
        return $this->view;
    }

    public function backgroundCropped($height = 100): static
    {
        $this->backgroundCroppedHeight = $height;
        $this->backgroundCropped = true;

        return $this;
    }

}
