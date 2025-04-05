<?php

namespace Modules\Billing\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;

class SubscriptionPlanGroupEditModal extends ModalComponent
{

    public $subscriptionPlanGroupId = false;
    public $state = [];
    public $groups = [];

    public function render()
    {
        return view('billing::admin.livewire.subscription-plan-group-edit-modal');
    }

    public function mount()
    {
        if ($this->subscriptionPlanGroupId) {
            $subscriptionPlan = SubscriptionPlanGroup::find($this->subscriptionPlanGroupId);
            if ($subscriptionPlan) {
                $this->state = $subscriptionPlan->toArray();
            }
        }
        $getGroups = SubscriptionPlanGroup::all();
        if ($getGroups->count() > 0) {
            $this->groups = $getGroups->toArray();
        }
    }

    public function save()
    {
        if ($this->subscriptionPlanGroupId) {

            // Update existing plan
            $subscriptionPlan = SubscriptionPlanGroup::find($this->subscriptionPlanGroupId);
            if ($subscriptionPlan) {
                $subscriptionPlan->fill($this->state);
                $subscriptionPlan->save();
            }
        } else {

            // Create new plan
            $subscriptionPlan = new SubscriptionPlanGroup();
            $subscriptionPlan->fill($this->state);
            $subscriptionPlan->save();

            $this->subscriptionPlanGroupId = $subscriptionPlan->id;
        }

        $this->emit('refreshSubscriptionPlanGroups');
        $this->closeModal();
    }
}
