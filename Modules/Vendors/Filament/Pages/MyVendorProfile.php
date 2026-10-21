<?php

namespace Modules\Vendors\Filament\Pages;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Vendors\Models\Vendor;
use Modules\Vendors\Services\VendorManager;

/**
 * Vendor self-service: edit the public storefront (name, slug, bio, logo,
 * banner, contact). Lives in the `profile` panel and is only visible to
 * users who carry the `vendor` role.
 */
class MyVendorProfile extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';
    protected string $view = 'modules.vendors::filament.pages.my-vendor-profile';
    protected static ?int $navigationSort = 10;

    public ?array $data = [];
    public ?Vendor $vendor = null;

    public function getTitle(): string
    {
        return __('My Shop');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Shop');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Vendor');
    }

    public function mount(): void
    {
        $this->vendor = app(VendorManager::class)->profileFor(Auth::user());

        $this->form->fill([
            'shop_name' => $this->vendor->shop_name,
            'slug' => $this->vendor->slug,
            'description' => $this->vendor->description,
            'logo' => $this->vendor->logo,
            'banner' => $this->vendor->banner,
            'contact_email' => $this->vendor->contact_email,
            'website' => $this->vendor->website,
            'notify_on_order' => $this->vendor->notify_on_order ?? true,
            'notify_channels' => $this->vendor->orderNotificationChannels(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('Storefront'))->columns(2)->schema([
                    TextInput::make('shop_name')
                        ->label(__('Shop name'))
                        ->required()
                        ->maxLength(191),
                    TextInput::make('slug')
                        ->label(__('URL slug'))
                        ->helperText(__('Your public address: /vendor/{slug}. Leave blank to auto-generate.'))
                        ->maxLength(191),
                    Textarea::make('description')
                        ->label(__('About your shop'))
                        ->columnSpanFull()
                        ->rows(4),
                    FileUpload::make('logo')
                        ->label(__('Logo'))
                        ->image()
                        ->disk('public')
                        ->directory('vendors')
                        ->visibility('public'),
                    FileUpload::make('banner')
                        ->label(__('Banner'))
                        ->image()
                        ->disk('public')
                        ->directory('vendors')
                        ->visibility('public'),
                    TextInput::make('contact_email')->label(__('Contact email'))->email(),
                    TextInput::make('website')->label(__('Website'))->url(),
                ]),

                Section::make(__('Order notifications'))->schema([
                    Toggle::make('notify_on_order')
                        ->label(__('Notify me when my products are ordered'))
                        ->default(true)
                        ->live(),
                    CheckboxList::make('notify_channels')
                        ->label(__('Notify me via'))
                        ->options([
                            Vendor::CHANNEL_MAIL => __('Email'),
                            Vendor::CHANNEL_DATABASE => __('In-app notifications'),
                        ])
                        ->default(Vendor::CHANNELS)
                        ->visible(fn ($get) => (bool) $get('notify_on_order'))
                        ->columns(2),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $data['logo'] = $this->normalizeUpload($data['logo'] ?? null);
        $data['banner'] = $this->normalizeUpload($data['banner'] ?? null);

        // A vendor never flips their own approval flag.
        unset($data['is_approved'], $data['user_id']);

        $this->vendor->fill($data)->save();

        Notification::make()
            ->title(__('Saved'))
            ->body(__('Your shop has been updated.'))
            ->success()
            ->send();
    }

    /**
     * FileUpload stores a disk-relative path (e.g. vendors/x.jpg). Store
     * the public URL so the frontend can render it directly as an <img src>.
     * Values that are already absolute URLs (admin-set) pass through.
     */
    protected function normalizeUpload($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        if (is_string($value) && preg_match('~^https?://~i', $value)) {
            return $value;
        }
        try {
            return Storage::disk('public')->url($value);
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    public function getSubheading(): ?string
    {
        if ($this->vendor && $this->vendor->slug) {
            return __('Public page:') . ' ' . url('vendor/' . $this->vendor->slug);
        }
        return null;
    }
}
