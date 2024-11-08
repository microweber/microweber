<?php

namespace Modules\Content\Concerns;

use Filament\Resources\Pages\EditRecord;

trait HasEditContentForms
{

    public function saveContentAndGoLiveEdit()
    {

        $this->saveContent();
        return $this->goLiveEdit();
    }

    public function saveContentAndGoLiveEditIframe()
    {

        $this->saveContent();
        return $this->goLiveEdit(true);
    }

    public function goLiveEdit($dispatchRedirectEvent = false)
    {
        $url = $this->record->link();

        if ($dispatchRedirectEvent) {
            $this->dispatch('mw-redirect-to-url', data: ['url'=>$url]);
        } else {
            return redirect($this->record->liveEditLink());

        }


    }

    public function saveContent(): void
    {
        $shouldRedirect = true;
        $shouldSendSavedNotification = true;


        //check if parent hase save
        if (method_exists(parent::class, 'save')) {
            parent::save($shouldRedirect, $shouldSendSavedNotification);
        } elseif (method_exists(parent::class, 'create')) {
            parent::create();
        }

        $additionalData = [];
        if (isset($this->seoForm) && $this->seoForm) {
            $additionalData = array_merge($additionalData, $this->seoForm->getState());
        }

        if (isset($this->advancedSettingsForm) && $this->advancedSettingsForm) {
            $additionalData = array_merge($additionalData, $this->advancedSettingsForm->getState());
        }

        if ($additionalData) {
            $this->record->fill($additionalData);
            $this->record->save();

        }
        $this->hasUnsavedDataChangesAlert = false;

    }


    /**
     * @return array
     */
    public function getEditContentForms(): array
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

            'advancedSettingsForm' => $this->form(static::getResource()::advancedSettingsForm(
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
