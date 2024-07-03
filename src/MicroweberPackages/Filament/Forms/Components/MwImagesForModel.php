<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Fields\MwSlugInput;
use Filament\Forms;

use Filament\Forms\Form;
use MicroweberPackages\Media\Models\Media;


class MwImagesForModel extends Field
{
    use Forms\Concerns\InteractsWithForms;


    protected string $view = 'filament-forms::components.mw-images-for-model';
    public $mediaIds = [];
    public $newImageUrl = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerListeners([
            'attachMediaIdToModel' => [
                function ($arguments): void {
                    dd($arguments);
                },
            ],
        ]);
    }

    // #[On('attachMediaIdToModel')]
    public function attachMediaIdToModel($id)
    {

        $this->mediaIds[] = $id;
        return $this;
    }


}
