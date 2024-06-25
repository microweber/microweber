<?php

namespace MicroweberPackages\Filament\Forms\Fields;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class MwSlugInput extends TextInput
{
    protected string $view = 'filament-title-with-slug::mw-slug-input';

    protected string|Closure|null $context = null;

    protected string|Closure $basePath = '/';

    protected string|Closure|null $baseUrl = null;

    protected bool $showUrl = true;

    protected bool $cancelled = false;

    protected Closure $recordSlug;

    protected bool|Closure $readOnly = false;

    protected string $labelPrefix;

    protected ?Closure $visitLinkRoute = null;

    protected string|Closure|null $visitLinkLabel = null;

    protected bool|Closure $slugInputUrlVisitLinkVisible = true;

    protected ?Closure $slugInputModelName = null;

    protected string|Closure|null $slugLabelPostfix = null;

    public function slugInputUrlVisitLinkVisible(bool|Closure $slugInputUrlVisitLinkVisible): static
    {
        $this->slugInputUrlVisitLinkVisible = $slugInputUrlVisitLinkVisible;

        return $this;
    }

    public function getSlugInputUrlVisitLinkVisible(): ?string
    {
        return $this->evaluate($this->slugInputUrlVisitLinkVisible);
    }

    public function slugInputModelName(?Closure $slugInputModelName): static
    {
        $this->slugInputModelName = $slugInputModelName;

        return $this;
    }

    public function getSlugInputModelName(): ?string
    {
        return $this->evaluate($this->slugInputModelName);
    }

    public function slugInputVisitLinkRoute(?Closure $visitLinkRoute): static
    {
        $this->visitLinkRoute = $visitLinkRoute;

        return $this;
    }

    public function getVisitLinkRoute(): ?string
    {
        return $this->evaluate($this->visitLinkRoute);
    }

    public function slugInputVisitLinkLabel(string|Closure|null $visitLinkLabel): static
    {
        $this->visitLinkLabel = $visitLinkLabel;

        return $this;
    }

    public function getVisitLinkLabel(): string
    {
        $label = $this->evaluate($this->visitLinkLabel);

        if ($label === '') {
            return '';
        }

        return $label ?: trans('filament-title-with-slug::package.permalink_label_link_visit') . ' ' . $this->getSlugInputModelName();
    }

    public function slugInputLabelPrefix(?string $labelPrefix): static
    {
        $this->labelPrefix = $labelPrefix ?? trans('filament-title-with-slug::package.permalink_label');

        return $this;
    }

    public function getLabelPrefix(): string
    {
        return $this->evaluate($this->labelPrefix);
    }

    public function readOnly(bool|Closure $condition = true): static
    {
        $this->readOnly = $condition;

        return $this;
    }

    public function getReadOnly(): string
    {
        return $this->evaluate($this->readOnly);
    }

    public function slugInputContext(string|Closure|null $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->evaluate($this->context);
    }

    public function slugInputSlugLabelPostfix(string|Closure|null $slugLabelPostfix): static
    {
        $this->slugLabelPostfix = $slugLabelPostfix;

        return $this;
    }

    public function getSlugLabelPostfix(): ?string
    {
        return $this->evaluate($this->slugLabelPostfix);
    }

    public function slugInputRecordSlug(Closure $recordSlug): static
    {
        $this->recordSlug = $recordSlug;

        return $this;
    }

    public function getRecordSlug(): ?string
    {
        return $this->evaluate($this->recordSlug);
    }

    public function getRecordUrl(): ?string
    {
        if (!$this->getRecordSlug()) {
            return null;
        }

        $visitLinkRoute = $this->getVisitLinkRoute();

        return $visitLinkRoute
            ? $this->getVisitLinkRoute()
            : $this->getBaseUrl() . $this->getBasePath() . $this->evaluate($this->recordSlug);
    }

    public function slugInputBasePath(string|Closure|null $path): static
    {
        $this->basePath = !is_null($path) ? $path : $this->basePath;

        return $this;
    }

    public function slugInputBaseUrl(string|Closure|null $url): static
    {
        $this->baseUrl = $url ?: config('app.url');

        return $this;
    }

    public function getBaseUrl(): string
    {
        return Str::of($this->evaluate($this->baseUrl))->rtrim('/');
    }

    public function slugInputShowUrl(bool $showUrl): static
    {
        $this->showUrl = $showUrl;

        return $this;
    }

    public function getShowUrl(): ?bool
    {
        return $this->showUrl;
    }

    public function getFullBaseUrl(): ?string
    {
        return $this->showUrl
            ? $this->getBaseUrl() . $this->getBasePath()
            : $this->getBasePath();
    }

    public function getBasePath(): string
    {
        return $this->evaluate($this->basePath);
    }
}
