{{--
 type: layout
 name: Feature 52
 position: 52
 categories: Features
--}}

<style>
    .feature-52 {
        border-top-right-radius: 500px;
        border-bottom-right-radius: 500px;
    }

    .feature-52 .counter {
        text-align: center;
        margin-bottom: 40px;
    }

    .feature-52 h2 {
        color: #fff;
        font-size: 48px;
        font-weight: 700;
    }

    .feature-52 p {
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        margin-top: 15px;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section feature-52"
    field-name="layout-feature-skin-52"
    container-class="mw-layout-container container no-element container edit"
>
    <x-row>
                <div class="col-lg-12">
                    <div class="wrapper">
                        <x-row>
                            <div class="col-xl-3 col-md-6 cloneable element">
                                <div class="counter">
                                    <h2>150 +</h2>
                                    <p class="count-text">Happy Students</p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 cloneable element">
                                <div class="counter">
                                    <h2>804 +</h2>
                                    <p class="count-text">Course Hours</p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 cloneable element">
                                <div class="counter">
                                    <h2>150 +</h2>
                                    <p class="count-text">Employed Students</p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 cloneable element">
                                <div class="counter end">
                                    <h2>15 +</h2>
                                    <p class="count-text">Years Experience</p>
                                </div>
                            </div>
                        </x-row>
                    </div>
                </div>
            </x-row>
</x-layout-section>
