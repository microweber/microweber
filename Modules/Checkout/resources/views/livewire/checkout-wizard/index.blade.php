<div class="checkout-wizard-container">
    <div class="max-w-5xl mx-auto">
        {{ $this->form }}
    </div>
</div>

<style>
    .checkout-wizard-container {
        padding: 1.5rem;
    }

    .checkout-wizard-container .fi-fo-wizard-header {
        margin-bottom: 2rem;
    }

    .checkout-wizard-container .fi-fo-wizard-header-step {
        padding: 0.75rem;
    }

    .checkout-wizard-container .fi-fo-wizard-header-step-button {
        font-weight: 500;
    }

    @media (max-width: 640px) {
        .checkout-wizard-container {
            padding: 1rem;
        }
    }
</style>
