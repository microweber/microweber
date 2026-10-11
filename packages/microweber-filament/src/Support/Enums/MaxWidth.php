<?php

namespace MicroweberPackages\Filament\Support\Enums;

/**
 * Backward compatibility enum for Filament v5 Width.
 * In Filament v5, the enum was renamed from MaxWidth to Width.
 * This enum provides backward compatibility for existing code.
 */
enum MaxWidth: string
{
    case ExtraSmall = 'xs';
    case Small = 'sm';
    case Medium = 'md';
    case Large = 'lg';
    case ExtraLarge = 'xl';
    case TwoExtraLarge = '2xl';
    case ThreeExtraLarge = '3xl';
    case FourExtraLarge = '4xl';
    case FiveExtraLarge = '5xl';
    case SixExtraLarge = '6xl';
    case SevenExtraLarge = '7xl';
    case Full = 'full';
    case MinContent = 'min';
    case MaxContent = 'max';
    case FitContent = 'fit';
    case Prose = 'prose';
    case ScreenSmall = 'screen-sm';
    case ScreenMedium = 'screen-md';
    case ScreenLarge = 'screen-lg';
    case ScreenExtraLarge = 'screen-xl';
    case ScreenTwoExtraLarge = 'screen-2xl';
    case Screen = 'screen';
}
