<?php

namespace Modules\WhiteLabel\Filament\Admin;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use MicroweberPackages\SystemLicenses\Models\SystemLicense;

class WhiteLabelLicenseManager extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->heading('White Label License Management')
            ->description('Manage your White Label module license to access all features')
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
                            ->helperText('Enter your White Label license key.')
                            ->autocomplete(true)
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
                    ])
                    ->action(function (array $data) {
                        // License is already saved in the validation
                        // Dispatch refresh event to parent page
                        $this->dispatch('license-updated');

                        // Refresh the page to update license status
                     //   return redirect()->to(request()->url());
                    }),
            ])
            ->query(SystemLicense::query())
            ->columns([
                TextColumn::make('local_key')
                    ->label('License Key'),
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
                    ->action(fn (SystemLicense $record) => $record->delete())
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('modules.white_label::filament.admin.license-manager');
    }
}
