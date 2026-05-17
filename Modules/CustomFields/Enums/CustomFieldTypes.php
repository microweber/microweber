<?php

namespace Modules\CustomFields\Enums;

use Filament\Support\Contracts\HasLabel;
use JaOcero\RadioDeck\Contracts\HasDescriptions;
use JaOcero\RadioDeck\Contracts\HasIcons;

enum CustomFieldTypes: string implements HasLabel, HasDescriptions, HasIcons
{
    // Most commonly used fields
    case TEXT = 'text';



    // Form interaction fields
    case DROPDOWN = 'dropdown';
    case RADIO = 'radio';
    case CHECKBOX = 'checkbox';


    // number fields
    case PHONE = 'phone';
    case NUMBER = 'number';

    // E-commerce related
    case PRICE = 'price';

    // Date and time
    case DATE = 'date';
    case TIME = 'time';

    // Location and address
    case ADDRESS = 'address';
    case COUNTRY = 'country';

    // Web and media
    case SITE = 'site';
    case UPLOAD = 'upload';
    case COLOR = 'color';



    // Utility fields
    case HIDDEN = 'hidden';
    case PROPERTY = 'property';
    case BREAKLINE = 'breakline';
    case EMAIL = 'email';
//    case BUTTON = 'button';


    public function getLabel(): ?string
    {
        // AI-788 (task-2026-05-17-c188de) — modern web style: "Email" not
        // "E-mail", "Website" not "Web Site". Plus consistency pass —
        // drop redundant " Field" suffix from labels (it's the wizard
        // for picking a field type; "Text Field" labels the OPTION,
        // doubled "field" noise). "Single Choice" / "Multiple choices"
        // capitalisation made consistent.
        return match ($this) {
            // Most commonly used fields
            self::TEXT => 'Text',

            self::PHONE => 'Phone',
            self::NUMBER => 'Number',

            // Form interaction fields
            self::DROPDOWN => 'Dropdown',
            self::RADIO => 'Single choice',
            self::CHECKBOX => 'Multiple choice',

            // E-commerce related
            self::PRICE => 'Price',

            // Date and time
            self::DATE => 'Date',
            self::TIME => 'Time',

            // Location and address
            self::ADDRESS => 'Address',
            self::COUNTRY => 'Country',

            // Web and media
            self::SITE => 'Website',
            self::UPLOAD => 'File upload',
            self::COLOR => 'Color',

            // Utility fields
            self::HIDDEN => 'Hidden',
            self::PROPERTY => 'Property',
            self::BREAKLINE => 'Section break',
            self::EMAIL => 'Email',
       //     self::BUTTON => 'Button',
        };
    }

    public function getDescriptions(): ?string
    {
        // AI-788 (task-2026-05-17-c188de) — actionable descriptions
        // replace the previous tautological "X field" copy. Each line
        // explains WHEN to pick this field type so the customer doesn't
        // have to guess the difference between Text vs Property vs
        // Hidden, or Dropdown vs Single Choice vs Multiple Choice.
        return match ($this) {
            // Most commonly used fields
            self::TEXT => 'Single line of free-form text',

            self::PHONE => 'Formatted phone number with country code',
            self::NUMBER => 'Numeric input (integer or decimal)',

            // Form interaction fields
            self::DROPDOWN => 'Pick one option from a closed list',
            self::RADIO => 'Pick one option visible as buttons',
            self::CHECKBOX => 'Pick multiple options from a list',

            // E-commerce related
            self::PRICE => 'Currency-formatted money amount',

            // Date and time
            self::DATE => 'Calendar date picker',
            self::TIME => 'Time-of-day picker',

            // Location and address
            self::ADDRESS => 'Street, city, postal code, country',
            self::COUNTRY => 'Country picker from ISO list',

            // Web and media
            self::SITE => 'Validated URL input',
            self::UPLOAD => 'Upload a file (image, document, etc.)',
            self::COLOR => 'Visual color picker with hex output',

            // Utility fields
            self::HIDDEN => 'Stored but not shown to the visitor',
            self::PROPERTY => 'Static label-value pair',
            self::BREAKLINE => 'Visual divider between form sections',
            self::EMAIL => 'Validated email address',
//            self::BUTTON => 'Button field',
        };
    }

    /**
     * AI-788 (task-2026-05-17-c188de) — group metadata. Returns the
     * human-readable group name per case so the Custom Field wizard
     * can render the 17 options under 7 grouped sections instead of
     * a flat 3-col grid. The wizard UI restructure (rendering the
     * groups visually) is deferred to AI-788b — this method gives
     * future UI work the data layer it needs.
     */
    public function getGroup(): string
    {
        return match ($this) {
            self::TEXT, self::PHONE, self::NUMBER, self::EMAIL => 'Basic input',
            self::DROPDOWN, self::RADIO, self::CHECKBOX => 'Choice',
            self::PRICE => 'Commerce',
            self::DATE, self::TIME => 'Date & time',
            self::ADDRESS, self::COUNTRY => 'Location',
            self::SITE, self::UPLOAD, self::COLOR => 'Web & media',
            self::HIDDEN, self::PROPERTY, self::BREAKLINE => 'Utility',
        };
    }

    public function getIcons(): ?string
    {
        return match ($this) {
            // Most commonly used fields
            self::TEXT => 'mw-text',

            self::PHONE => 'heroicon-o-phone',
            self::NUMBER => 'mw-numbers',

            // Form interaction fields
            self::DROPDOWN => 'mw-dropdown',
            self::RADIO => 'mw-radio-checked',
            self::CHECKBOX => 'mw-checkbox',

            // E-commerce related
            self::PRICE => 'heroicon-o-currency-dollar',

            // Date and time
            self::DATE => 'heroicon-o-calendar-days',
            self::TIME => 'heroicon-o-clock',

            // Location and address
            self::ADDRESS => 'heroicon-o-map-pin',
            self::COUNTRY => 'heroicon-o-home',

            // Web and media
            self::SITE => 'heroicon-o-globe-europe-africa',
            self::UPLOAD => 'heroicon-o-arrow-up-tray',
            self::COLOR => 'heroicon-o-paint-brush',

            // Utility fields
            self::HIDDEN => 'mw-hidden',
            self::PROPERTY => 'mw-info',
            self::BREAKLINE => 'heroicon-o-pencil',

            self::EMAIL => 'heroicon-o-at-symbol',
//            self::BUTTON => 'heroicon-o-pencil',

        };
    }
}
