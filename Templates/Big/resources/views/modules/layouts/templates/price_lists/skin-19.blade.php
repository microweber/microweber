{{--
type: layout

name: Pricing Cards

position: 19

categories: Price Lists
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-price-lists-skin-19"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <div class="text-center mx-auto pb-4" style="max-width: 720px;">
                <h3 class="regular-mode" data-mwplaceholder="Enter title here">Choose Your Plan</h3>
                <p class="regular-mode" data-mwplaceholder="Enter text here">Pick the plan that works best for you. All plans include a 30-day money-back guarantee.</p>
            </div>

            <x-pricing-table :columns="3" class="mb-4 safe-mode">
                <x-pricing-row
                    plan-name="Starter"
                    price="$9"
                    period="/mo"
                    :features="['5 users included', '10 GB storage', 'Email support', 'Basic analytics']"
                    button-text="Get Started"
                    button-style="btn btn-outline-primary"
                />
                <x-pricing-row
                    plan-name="Professional"
                    price="$29"
                    period="/mo"
                    :features="['25 users included', '50 GB storage', 'Priority support', 'Advanced analytics', 'API access']"
                    :highlighted="true"
                    button-text="Choose Pro"
                />
                <x-pricing-row
                    plan-name="Enterprise"
                    price="$99"
                    period="/mo"
                    :features="['Unlimited users', '500 GB storage', '24/7 phone support', 'Custom integrations', 'Dedicated manager']"
                    button-text="Contact Sales"
                    button-style="btn btn-outline-primary"
                />
            </x-pricing-table>
</x-layout-section>
