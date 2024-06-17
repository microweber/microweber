<?php

namespace MicroweberPackages\CustomField\Enums;

use Filament\Support\Contracts\HasLabel;
use JaOcero\RadioDeck\Contracts\HasDescriptions;
use JaOcero\RadioDeck\Contracts\HasIcons;

enum CustomFieldTypes: string implements HasLabel, HasDescriptions, HasIcons
{
    case PRICE = 'price';
    case TEXT = 'text';
//    case BUTTON = 'button';
    case RADIO = 'radio';
    case DROPDOWN = 'dropdown';
    case CHECKBOX = 'checkbox';
    case NUMBER = 'number';
    case PHONE = 'phone';
    case SITE = 'site';
    case EMAIL = 'email';
    case ADDRESS = 'address';
    case COUNTRY = 'country';
    case DATE = 'date';
    case TIME = 'time';
    case COLOR = 'color';
    case UPLOAD = 'upload';
    case PROPERTY = 'property';
    case BREAKLINE = 'breakline';

    case HIDDEN = 'hidden';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::PRICE => 'Price',
            self::TEXT => 'Text Field',
       //     self::BUTTON => 'Button',
            self::RADIO => 'Single Choice',
            self::DROPDOWN => 'Dropdown',
            self::CHECKBOX => 'Multiple choices',
            self::NUMBER => 'Number',
            self::PHONE => 'Phone',
            self::SITE => 'Web Site',
            self::EMAIL => 'E-mail',
            self::ADDRESS => 'Address',
            self::COUNTRY => 'Country',
            self::DATE => 'Date',
            self::TIME => 'Time',
            self::COLOR => 'Color',
            self::UPLOAD => 'File Upload',
            self::PROPERTY => 'Property',
            self::BREAKLINE => 'Break Line',
            self::HIDDEN => 'Hidden Field',
        };
    }

    public function getDescriptions(): ?string
    {
        return match ($this) {
            self::PRICE => 'Price field',
            self::TEXT => 'Text field',
//            self::BUTTON => 'Button field',
            self::RADIO => 'Single choice field',
            self::DROPDOWN => 'Dropdown field',
            self::CHECKBOX => 'Multiple choices field',
            self::NUMBER => 'Number field',
            self::PHONE => 'Phone field',
            self::SITE => 'Web Site field',
            self::EMAIL => 'E-mail field',
            self::ADDRESS => 'Address field',
            self::COUNTRY => 'Country field',
            self::DATE => 'Date field',
            self::TIME => 'Time field',
            self::COLOR => 'Color field',
            self::UPLOAD => 'File Upload field',
            self::PROPERTY => 'Property field',
            self::BREAKLINE => 'Break Line field',
            self::HIDDEN => 'Hidden field',
        };
    }

    public function getIcons(): ?string
    {
        return match ($this) {
            self::PRICE => 'heroicon-o-currency-dollar',
            self::TEXT => 'mw-text',
//            self::BUTTON => 'heroicon-o-pencil',
            self::RADIO => 'mw-radio-checked',
            self::DROPDOWN => 'mw-dropdown',
            self::CHECKBOX => 'mw-checkbox',
            self::NUMBER => 'mw-numbers',
            self::PHONE => 'heroicon-o-phone',
            self::SITE => 'heroicon-o-globe-europe-africa',
            self::EMAIL => 'heroicon-o-at-symbol',
            self::ADDRESS => 'heroicon-o-map-pin',
            self::COUNTRY => 'heroicon-o-home',
            self::DATE => 'heroicon-o-calendar-days',
            self::TIME => 'heroicon-o-clock',
            self::COLOR => 'heroicon-o-paint-brush',
            self::UPLOAD => 'heroicon-o-arrow-up-tray',
            self::PROPERTY => 'mw-info',
            self::BREAKLINE => 'heroicon-o-pencil',
            self::HIDDEN => 'mw-hidden',

        };
    }
}
