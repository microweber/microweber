<?php
/*
type: layout
name: Text block 4 - Body with pull-aside
position: 4
categories: Text block
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section text-block-skin-4"
    field-name="layout-text-block-skin-4"
>
    <x-row>
        <x-col size="12" size-md="8">
            <h2 class="mb-4" data-mwplaceholder="Enter title here">Getting the most out of your first week</h2>
            <p data-mwplaceholder="Enter text here">The fastest way to feel at home is to set up your workspace before you invite anyone else in. Spend a few minutes naming your projects clearly and adding a short description to each one, so the people who join later understand the layout at a glance.</p>
            <p data-mwplaceholder="Enter text here">Once the structure is in place, start small. Move a single ongoing task into the system and run it through from start to finish. Seeing one real piece of work travel the full path teaches you more than reading any guide, and it surfaces the settings you will actually want to change.</p>
            <p data-mwplaceholder="Enter text here">By the end of the week you will have a rhythm that fits your team. From there, invite the rest of the group and let them adapt the space to their own habits rather than forcing everyone into a rigid template on day one.</p>
        </x-col>
        <x-col size="12" size-md="4">
            <div class="bg-light rounded p-4">
                <h5 class="mb-3" data-mwplaceholder="Enter title here">Quick tip</h5>
                <p class="mb-0" data-mwplaceholder="Enter text here">Pin the two or three projects you touch every day to the top of your sidebar. It sounds minor, but cutting the clicks it takes to reach your most active work pays off every single morning.</p>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
