<?php

namespace MicroweberPackages\Content\Concerns;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;

trait HasEditContentForms
{

    public function saveContent(): void
    {
        $shouldRedirect = true;
        $shouldSendSavedNotification = true;


        //check if parent hase save
        if (method_exists(parent::class, 'save')) {
            parent::save($shouldRedirect, $shouldSendSavedNotification);
        }elseif (method_exists(parent::class, 'create')) {
            parent::create();
        }


        $this->record->update(
            $this->seoForm->getState(),
        );
    }


    /**
     * @return array
     */
    public function getEditContentForms() : array
    {
        /** @var \Filament\Forms\Form $form */
        /** @var EditRecord $this */

        return [
            'form' => $this->form(static::getResource()::form(
                $this->makeForm()
                    ->operation('edit')
                    ->model($this->getRecord())
                    ->statePath($this->getFormStatePath())
                    ->columns($this->hasInlineLabels() ? 1 : 2)
                    ->inlineLabel($this->hasInlineLabels()),
            )),
            'seoForm' => $this->form(static::getResource()::seoForm(
                $this->makeForm()
                    ->operation('edit')
                    ->model($this->getRecord())
                    ->statePath($this->getFormStatePath())
                    ->columns($this->hasInlineLabels() ? 1 : 2)
                    ->inlineLabel($this->hasInlineLabels()),
            )),
        ];

    }
}
