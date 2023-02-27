<?php

namespace MicroweberPackages\Filament\Tables\Columns;

use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\HtmlString;

use Closure;

class ImageUrlColumn extends ImageColumn
{
    protected string | Closure | null $imageUrl = null;

    public function imageUrl(string | Closure | null $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->evaluate($this->imageUrl);
    }


}
