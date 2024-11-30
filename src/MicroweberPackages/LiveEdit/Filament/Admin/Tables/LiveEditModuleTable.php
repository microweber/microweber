<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Tables;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Testimonials\Models\Testimonial;

abstract class LiveEditModuleTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public string|null $rel_id = null;
    public string|null $rel_type = null;
    public string|null $module_id = null;


    #[On('resetTableList')]
    public function resetTableList($data)
    {
        if (isset($data['rel_id'])) {
            $this->rel_id = $data['rel_id'];
        }
        if (isset($data['rel_type'])) {
            $this->rel_type = $data['rel_type'];
        }
        $this->resetTable();
    }

    public function render()
    {
        return view('filament-panels::components.layout.table');
    }
}
