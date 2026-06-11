{{--
 type: layout
 name: Feature 51
 position: 51
 categories: Features
--}}

<style>
    .feature-51 .services-51-item:hover .services-51-icon i {
        margin-top: -10px;

    }

    .feature-51 .services-51-item {
        position: relative;
        margin-top: 95px;
    }

    .feature-51 .services-51-item .services-51-icon {
        width: 190px;
        height: 190px;
        display: inline-block;
        text-align: center;
        line-height: 190px;
        background-color: var(--mw-primary-color);
        border-radius: 50%;
        position: absolute;
        right: 0;
        top: -95px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-51 .services-51-item .services-51-icon i {
        max-width: 86px;
        transition: all .2s;
        font-size: 90px;
        color: var(--mw-text-on-dark-background-color);
    }

    .feature-51 .services-51-item .services-51-main-content {
        border-radius: 25px;
        padding: 80px 30px 50px 30px;
        background-color: #f1f0fe;
        margin-bottom: 30px;
        margin-right: 80px;
        transition: all .4s;
    }

    .feature-51 .services-51-item h4 {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 15px;
        line-height: 30px;
        transition: all .4s;
    }

    .feature-51 .services-51-item p {
        color: #4a4a4a;
        margin-bottom: 25px;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section feature-51"
    field-name="layout-feature-skin-51"
    container-class="mw-layout-container container no-element container edit"
>
    <x-row>
                <div class="col-xl-4 col-md-6 cloneable element safe-mode">
                    <div class="services-51-item">
                        <div class="services-51-icon background-color-element element">
                            <i class="mw-micon-Books"></i>

                        </div>
                        <div class="services-51-main-content background-color-element element">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Online Degrees</h4>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Whenever you need free templates in HTML CSS, you just remember TemplateMo website.</p>
                            <div class="services-51-main-button">
                                <module type="btn" text="Read More" button_style="btn-primary"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 cloneable element safe-mode">
                    <div class="services-51-item">
                        <div class="services-51-icon background-color-element element">
                            <i class="mw-micon-Laptop-Phone"></i>
                        </div>
                        <div class="services-51-main-content background-color-element element">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Short Courses</h4>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">You can browse free templates based on different tags such as digital marketing, etc.</p>
                            <div class="services-51-main-button">
                                <module type="btn" text="Read More" button_style="btn-primary"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 cloneable element safe-mode">
                    <div class="services-51-item">
                        <div class="services-51-icon background-color-element element">
                            <i class="mw-micon-Professor"></i>
                        </div>
                        <div class="services-51-main-content background-color-element element">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Web Experts</h4>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">You can start learning HTML CSS by modifying free templates from our website too.</p>
                            <div class="services-51-main-button">
                                <module type="btn" text="Read More" button_style="btn-primary"/>
                            </div>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
