<?php

namespace Modules\Billing\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;

class SubscriptionPlanEditModal extends ModalComponent
{

    public $subscriptionPlanId = false;
    public $state = [];
    public $groups = [];
    public $plans = [];

    public function render()
    {
        return view('billing::admin.livewire.subscription-plan-edit-modal');
    }

    public function mount()
    {
        if ($this->subscriptionPlanId) {
            $subscriptionPlan = SubscriptionPlan::find($this->subscriptionPlanId);
            if ($subscriptionPlan) {
                $this->state = $subscriptionPlan->toArray();
            }
        }
        $getGroups = SubscriptionPlanGroup::all();
        if ($getGroups->count() > 0) {
            $this->groups = $getGroups->toArray();
        }
        $getPlans = SubscriptionPlan::all();
        if ($getPlans->count() > 0) {
            $this->plans = $getPlans->toArray();
        }
    }

    public function save()
    {
        if (!empty($this->state['sku'])) {
            $this->state['sku'] = trim($this->state['sku']);
            $this->state['sku'] = strtolower($this->state['sku']);
            $this->state['sku'] = str_replace(' ', '_', $this->state['sku']);
        }

        if (!empty($this->state['description'])) {
            $this->state['description'] = $this->state['description'];
        }

        if ($this->subscriptionPlanId) {

            // Update existing plan
            $subscriptionPlan = SubscriptionPlan::find($this->subscriptionPlanId);
            if ($subscriptionPlan) {
                $subscriptionPlan->fill($this->state);
                $subscriptionPlan->save();
            }
        } else {

            // Create new plan
            $subscriptionPlan = new SubscriptionPlan();
            $subscriptionPlan->fill($this->state);
            $subscriptionPlan->save();

            $this->subscriptionPlanId = $subscriptionPlan->id;
        }

        $this->emit('refreshSubscriptionPlans');
        $this->closeModal();
    }
}
