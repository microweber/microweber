{{--
 type: layout
 name: Feature 54
 position: 54
 categories: Features
--}}

<style>
    .mw-features-54-featured-numbers {
        line-height: normal;
        display: block;
        margin-bottom: 0;
    }

    .mw-features-54-featured-text {
        color: var(--mw-primary-color);
    }

    .mw-features-54-featured-border-bottom {
        border-bottom: 1px solid #ececec;
    }

    .mw-features-54-featured-border-start {
        border-left: 1px solid #ececec;
    }

    .profile-thumb {
        border: 1px solid #ececec;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .profile-title {
        border-bottom: 1px solid #ececec;
        padding: 15px 30px;
    }

    .profile-small-title {
        border-right: 1px solid #ececec;
        color: var(--mw-primary-color);
        font-weight: bold;
        min-width: 140px;
        margin-right: 10px;
        padding: 13px 30px;
        display: inline-block;
    }

    .profile-body p {
        margin-bottom: 0;
    }

    .profile-body p:nth-of-type(even) {
        background: #fff;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-features-54-featured section-padding feature-54"
    background-attrs='data-background-color="#F9F9F9"'
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
        <div class="container mw-layout-container no-element container edit" field="layout-feature-skin-54-{{ $params['id'] }}" rel="module">
            <x-row class="align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="profile-thumb">
                        <div class="profile-title">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">Information</h4>
                        </div>

                        <div class="profile-body">
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">
                                <span class="profile-small-title">Name</span>
                                <span>Joshua Morgan</span>
                            </p>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">
                                <span class="profile-small-title">Birthday</span>
                                <span>Aug 12, 1986</span>
                            </p>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">
                                <span class="profile-small-title">Phone</span>
                                <span><a href="tel: 305-240-9671">120-240-9600</a></span>
                            </p>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">
                                <span class="profile-small-title">Email</span>
                                <span><a href="mailto:hello@josh.design">hello@josh.design</a></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mt-5 mt-lg-0">
                    <div class="about-thumb">
                        <x-row class="px-md-3">
                            <div class="col-lg-6 col-6 mw-features-54-featured-border-bottom py-2">
                                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-section-title mw-features-54-featured-numbers">20+</h1>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-features-54-featured-text">Years of Experiences</p>
                            </div>

                            <div class="col-lg-6 col-6 mw-features-54-featured-border-start mw-features-54-featured-border-bottom ps-5 py-2">
                                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-section-title mw-features-54-featured-numbers">245</h1>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-features-54-featured-text">Happy Customers</p>
                            </div>

                            <div class="col-lg-6 col-6 pt-4">
                                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-section-title mw-features-54-featured-numbers">640</h1>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-features-54-featured-text">Project Finished</p>
                            </div>

                            <div class="col-lg-6 col-6 mw-features-54-featured-border-start ps-5 pt-4">
                                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-section-title mw-features-54-featured-numbers">72+</h1>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-features-54-featured-text">Digital Awards</p>
                            </div>
                        </x-row>
                    </div>
                </div>
            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>
