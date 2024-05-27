<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Filament\Admin;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use MicroweberPackages\App\Models\SystemLicenses;

class ListLicenses extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {

//        "created_by" => null
//        "edited_by" => null
//        "rel_type" => null
//        "rel_id" => null
//        "local_key" => ""
//        "local_key_hash" => null
//        "registered_name" => ""
//        "company_name" => null
//        "domains" => null
//        "status" => "Active"
//        "product_id" => null
//        "service_id" => 0
//        "billing_cycle" => null
//        "reg_on" => null
//        "due_on" => null

        return $table
            ->headerActions([
                Action::make('license-create')
                    ->label('Add License')
                    ->form([
                        TextInput::make('local_key')
                            ->label('License Key')
                            ->helperText('Enter your license key.')
                            ->rules([
                                fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {

                                    $update_api = mw('update');
                                    $validateLicense = $update_api->save_license([
                                        'local_key' => $value
                                    ]);
                                    if (isset($validateLicense['is_active'])) {
                                        return true;
                                    } else {
                                        $fail('Invalid license key.');
                                    }
                                },
                            ]),
                    ])
                    ->afterFormValidated(function () {
                       $newLicense = new SystemLicenses();
                       $newLicense->local_key = 1;
                       $newLicense->save();
                    }),
            ])
            ->query(SystemLicenses::query())
            ->columns([
                TextColumn::make('local_key')
                    ->label('Key'),
                TextColumn::make('registered_name')
                    ->label('Owner'),
                TextColumn::make('status')
                    ->badge(true)
                    ->label('Status')
            ])
            ->filters([
                // ...
            ])
            ->actions([
//                Action::make('edit')
//                    ->url(fn (SystemLicenses $record): string => route('posts.edit', $record))
//                    ->openUrlInNewTab(),

                Action::make('license-delete')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (SystemLicenses $record) => $record->delete())
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('marketplace::livewire.filament.admin.list-licenses');
    }
}
