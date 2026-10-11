<?php

namespace MicroweberPackages\Admin\Filament;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * MW v2 admin avatar — light-grey chip (#EDEFF3) with ink initials (#182433),
 * matching the demo. Filament's default UiAvatarsProvider renders a near-black
 * (Gray[950]) circle with white text, which doesn't match the MW look.
 */
class MwAvatarProvider implements AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        $name = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        return 'https://ui-avatars.com/api/?name=' . urlencode($name)
            . '&format=svg&bold=true&color=182433&background=EDEFF3';
    }
}
