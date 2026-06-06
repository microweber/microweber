<?php

namespace Modules\Comments\Filament\Resources\CommentResource\Pages;

use Modules\Comments\Filament\Resources\CommentResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        // task-2026-06-06-AI759 — one primary CTA per screen. "New comment"
        // (CreateAction) is the single primary action. "Comments Settings" is
        // admin-meta config, not a primary user action, so it is demoted to a
        // secondary gray outlined button — same hierarchy the Orders list uses
        // (AI-783 grouped its settings under a gear ActionGroup). Previously both
        // rendered as filled primary pills side-by-side with no hierarchy.
        return [
            Actions\CreateAction::make()
                ->color('primary'),
            Action::make('settings')
                ->label('Comments Settings')
                ->url(route('filament.admin.pages.comments-module-settings-admin'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->outlined(),
        ];
    }
}
