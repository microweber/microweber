<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport\Tests\Fixtures;

use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

/**
 * Test fixture: a Livewire component with nested Filament actions.
 *
 * Action tree:
 *   centeredAction (centered modal)
 *     └── nestedSlideOverAction (slideOver inside centered)
 *           └── deepCenteredAction (centered inside slideOver inside centered)
 *   slideOverAction (slideOver modal)
 *     └── nestedCenteredAction (centered inside slideOver)
 *           └── deepSlideOverAction (slideOver inside centered inside slideOver)
 *   tripleNestedAction (centered → slideOver → centered → slideOver)
 */
class NestedModalComponent extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    // ─── Level 1: Centered modal ────────────────────────────────────────

    public function centeredAction(): Action
    {
        return Action::make('centered')
            ->label('Open Centered Modal')
            ->modalHeading('Centered Modal (Level 1)')
            ->modalWidth('lg')
            ->schema([
                TextInput::make('centered_field')
                    ->label('Centered Field'),
            ])
            ->action(fn () => null)
            ->registerModalActions([
                $this->nestedSlideOverAction(),
            ]);
    }

    // ─── Level 2: SlideOver inside Centered ─────────────────────────────

    public function nestedSlideOverAction(): Action
    {
        return Action::make('nestedSlideOver')
            ->label('Open Nested SlideOver')
            ->modalHeading('SlideOver Modal (Level 2)')
            ->slideOver()
            ->schema([
                TextInput::make('nested_slideover_field')
                    ->label('Nested SlideOver Field'),
            ])
            ->action(fn () => null)
            ->registerModalActions([
                $this->deepCenteredAction(),
            ]);
    }

    // ─── Level 3: Centered inside SlideOver inside Centered ─────────────

    public function deepCenteredAction(): Action
    {
        return Action::make('deepCentered')
            ->label('Open Deep Centered Modal')
            ->modalHeading('Deep Centered Modal (Level 3)')
            ->modalWidth('md')
            ->schema([
                TextInput::make('deep_centered_field')
                    ->label('Deep Centered Field'),
            ])
            ->action(fn () => null);
    }

    // ─── Level 1: SlideOver modal ───────────────────────────────────────

    public function slideOverAction(): Action
    {
        return Action::make('slideOver')
            ->label('Open SlideOver Modal')
            ->modalHeading('SlideOver Modal (Level 1)')
            ->slideOver()
            ->schema([
                TextInput::make('slideover_field')
                    ->label('SlideOver Field'),
            ])
            ->action(fn () => null)
            ->registerModalActions([
                $this->nestedCenteredAction(),
            ]);
    }

    // ─── Level 2: Centered inside SlideOver ─────────────────────────────

    public function nestedCenteredAction(): Action
    {
        return Action::make('nestedCentered')
            ->label('Open Nested Centered')
            ->modalHeading('Nested Centered Modal (Level 2)')
            ->modalWidth('lg')
            ->schema([
                TextInput::make('nested_centered_field')
                    ->label('Nested Centered Field'),
            ])
            ->action(fn () => null)
            ->registerModalActions([
                $this->deepSlideOverAction(),
            ]);
    }

    // ─── Level 3: SlideOver inside Centered inside SlideOver ────────────

    public function deepSlideOverAction(): Action
    {
        return Action::make('deepSlideOver')
            ->label('Open Deep SlideOver')
            ->modalHeading('Deep SlideOver Modal (Level 3)')
            ->slideOver()
            ->schema([
                TextInput::make('deep_slideover_field')
                    ->label('Deep SlideOver Field'),
            ])
            ->action(fn () => null);
    }

    // ─── Level 1: Triple-nested chain ───────────────────────────────────

    public function tripleNestedAction(): Action
    {
        return Action::make('tripleNested')
            ->label('Open Triple Nested')
            ->modalHeading('Triple Nested Level 1 (Centered)')
            ->modalWidth('lg')
            ->schema([
                TextInput::make('triple_field_1')
                    ->label('Triple Field 1'),
            ])
            ->action(fn () => null)
            ->registerModalActions([
                Action::make('tripleLevel2')
                    ->label('Level 2 SlideOver')
                    ->modalHeading('Triple Nested Level 2 (SlideOver)')
                    ->slideOver()
                    ->schema([
                        TextInput::make('triple_field_2')
                            ->label('Triple Field 2'),
                    ])
                    ->action(fn () => null)
                    ->registerModalActions([
                        Action::make('tripleLevel3')
                            ->label('Level 3 Centered')
                            ->modalHeading('Triple Nested Level 3 (Centered)')
                            ->modalWidth('md')
                            ->schema([
                                TextInput::make('triple_field_3')
                                    ->label('Triple Field 3'),
                            ])
                            ->action(fn () => null)
                            ->registerModalActions([
                                Action::make('tripleLevel4')
                                    ->label('Level 4 SlideOver')
                                    ->modalHeading('Triple Nested Level 4 (SlideOver)')
                                    ->slideOver()
                                    ->schema([
                                        TextInput::make('triple_field_4')
                                            ->label('Triple Field 4'),
                                    ])
                                    ->action(fn () => null),
                            ]),
                    ]),
            ]);
    }

    public function render(): string
    {
        return <<<'BLADE'
        <div>
            {{ $this->centeredAction }}
            {{ $this->slideOverAction }}
            {{ $this->tripleNestedAction }}

            <x-filament-actions::modals />
        </div>
        BLADE;
    }
}
