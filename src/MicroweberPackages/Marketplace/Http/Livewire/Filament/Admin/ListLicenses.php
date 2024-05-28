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
use Illuminate\Support\HtmlString;
use Livewire\Component;
use MicroweberPackages\App\Models\SystemLicenses;

class ListLicenses extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->heading('Check your licenses')
            ->description('From this modal you can manipulate your licenses')
            ->headerActions([
                Action::make('licenses-refresh')
                    ->label('Refresh Licenses')
                    ->action(function () {
                        $updateApi = mw('update');
                        $validateLicense = $updateApi->validate_license();
                    }),
                Action::make('license-create')
                    ->label('Add License')
                    ->form([
                        TextInput::make('local_key')
                            ->label('License Key')
                            ->helperText('Enter your license key.')
                            ->hint(function () {
                                return new HtmlString("<a href='https://microweber.com/pricing#white-label' target='_blank'>You don't have a license key?</a>");
                            })
                            ->rules([
                                fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {

                                    $updateApi = mw('update');
                                    $validateLicense = $updateApi->save_license([
                                        'local_key' => $value
                                    ]);
                                    if (isset($validateLicense['is_active'])) {
                                        return true;
                                    } else {
                                        $fail('Invalid license key.');
                                    }
                                },
                            ]),
                    ]),
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
                Action::make('license-delete')
                    ->label('Delete')
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
