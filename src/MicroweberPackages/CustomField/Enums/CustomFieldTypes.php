<?php

namespace MicroweberPackages\CustomField\Enums;

use Filament\Support\Contracts\HasLabel;
use JaOcero\RadioDeck\Contracts\HasDescriptions;
use JaOcero\RadioDeck\Contracts\HasIcons;

enum CustomFieldTypes: string implements HasLabel, HasDescriptions, HasIcons
{
    case PRICE = 'price';
    case TEXT = 'text';
    case BUTTON = 'button';
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
            self::BUTTON => 'Button',
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
            self::BUTTON => 'Button field',
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
            self::PRICE => 'heroicon-m-device-phone-mobile',
            self::TEXT => 'heroicon-o-pencil',
            self::BUTTON => 'heroicon-o-pencil',
            self::RADIO => 'heroicon-o-pencil',
            self::DROPDOWN => 'heroicon-o-pencil',
            self::CHECKBOX => 'heroicon-o-pencil',
            self::NUMBER => 'heroicon-o-pencil',
            self::PHONE => 'heroicon-o-pencil',
            self::SITE => 'heroicon-o-pencil',
            self::EMAIL => 'heroicon-o-pencil',
            self::ADDRESS => 'heroicon-o-pencil',
            self::COUNTRY => 'heroicon-o-pencil',
            self::DATE => 'heroicon-o-pencil',
            self::TIME => 'heroicon-o-pencil',
            self::COLOR => 'heroicon-o-pencil',
            self::UPLOAD => 'heroicon-o-pencil',
            self::PROPERTY => 'heroicon-o-pencil',
            self::BREAKLINE => 'heroicon-o-pencil',
            self::HIDDEN => 'heroicon-o-pencil',

        };
    }
}
