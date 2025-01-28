<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Components\{
    Livewire,
    Select,
    Tabs
};
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Teamcard\Models\Teamcard;

/**
 * Team Card Module Settings
 *
 * Manages the settings and configuration for the Team Card module
 */
class TeamcardModuleSettings extends LiveEditModuleSettingsTable
{
    /**
     * Module configuration
     */
    public string $module = 'teamcard';
    public string $modelName = Teamcard::class;
    public string $tableComponentName = TeamcardTableList::class;

    /**
     * Build the form schema for the module settings
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->buildMainTabs()
            ]);
    }

    /**
     * Build the main tabs structure
     */
    protected function buildMainTabs(): Tabs
    {
        return Tabs::make('Teamcard')
            ->tabs([
                $this->buildMainSettingsTab(),
                $this->buildDesignTab(),
                $this->buildAdvancedTab(),
            ]);
    }

    /**
     * Build the main settings tab
     */
    protected function buildMainSettingsTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Main settings')
            ->schema([
                Livewire::make($this->tableComponentName, [
                    'rel_id' => $this->getRelId(),
                    'rel_type' => $this->getRelType(),
                    'module_id' => $this->getModuleId(),
                ])
            ]);
    }

    /**
     * Build the design tab
     */
    protected function buildDesignTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Design')
            ->schema($this->getTemplatesFormSchema());
    }

    /**
     * Build the advanced tab
     */
    protected function buildAdvancedTab(): Tabs\Tab
    {
        return Tabs\Tab::make('Advanced')
            ->schema($this->getDataSourceFormSchema());
    }

    /**
     * Get the module ID from parameters
     */
    protected function getModuleId(): ?string
    {
        return $this->params['id'] ?? null;
    }

    /**
     * Get the relation ID from options or parameters
     */
    protected function getRelId(): ?string
    {
        return $this->getOption('rel_id')
            ?? $this->params['rel_id']
            ?? $this->params['id']
            ?? null;
    }

    /**
     * Get the relation type from options or parameters
     */
    protected function getRelType(): string
    {
        return $this->getOption('rel_type')
            ?? $this->params['rel_type']
            ?? 'module';
    }
}
