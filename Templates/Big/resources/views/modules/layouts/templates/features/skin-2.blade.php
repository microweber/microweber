{{--
 type: layout
 name: Feature 2
 position: 2
 categories: Features
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-2"
    field-name="layout-feature-skin-2"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h4">The Feature Title</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="text-center mt-7">
        <x-feature-item icon="mw-micon-Add-User" text="To get started in learning how to observe the stars much better, there are some basic things." class="mx-auto">
            <module class="mt-md-4 mt-3" type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
        </x-feature-item>

        <x-feature-item icon="mw-micon-Add-UserStar" text="To get started in learning how to observe the stars much better, there are some basic things." class="mx-auto">
            <module class="mt-md-4 mt-3" type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
        </x-feature-item>

        <x-feature-item icon="mw-micon-Administrator" text="To get started in learning how to observe the stars much better, there are some basic things." class="mx-auto">
            <module class="mt-md-4 mt-3" type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
        </x-feature-item>
    </x-row>
</x-layout-section>
