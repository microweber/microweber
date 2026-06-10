{{--
 type: layout
 name: Feature 46
 position: 46
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} ">
    <module type="background" data-background-color="#F9FAFB;" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-feature-skin-46-{{ $params['id'] }}" rel="module">
        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-center mb-5">The Best Features We Present</h2>
        <div class="row">
            <div class="col-xl-10 d-flex justify-content-center mx-auto text-center text-lg-start flex-wrap">
                <div class="col-12 col-lg-8 col-xl-6 cloneable element safe-mode background-color-element p-3">
                    <div class="h-100 p-5" style="background-color: #ffffff;">
                        <div class="d-flex align-items-center justify-content-center mb-4 mx-xl-0 mx-auto cloneable element safe-mode background-color-element" style="background-color: rgba(54, 65, 183, 0.2); width: 70px; height: 70px; border-radius: 50%;">
                            <i class="icon-size-32px mw-micon-Add-Bag" style="color: #3641B7;"></i>
                        </div>
                        <div class="col-xl-10">
                            <div class="regular-mode">
                                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Best Technology Tools</h5>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">Finding the perfect learning tool for Flash is a daunting task to any novice web developer. One can find help in a number of ways through books, friends and private tutors.</p>
                            </div>
                        </div>
                        <module type="btn" class="mt-3" text="Learn More" button_style="btn-primary" button_size=""/>
                    </div>
                </div>

                <div class="col-12 col-lg-8 col-xl-6 cloneable element safe-mode background-color-element p-3">
                    <div class="h-100 d-flex flex-column p-5" style="background-color: #ffffff;">
                        <div class="d-flex align-items-center justify-content-center mb-4 mx-xl-0 mx-auto cloneable element safe-mode background-color-element" style="background-color: rgba(255, 86, 112, 0.2); width: 70px; height: 70px; border-radius: 50%;">
                            <i class="icon-size-32px mw-micon-Add-File safe-element no-typing" style="color: #FF5670;"></i>
                        </div>
                        <div class="col-xl-10">
                            <div class="regular-mode">
                                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Fast & Responsive Result</h5>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">Finding the perfect learning tool for Flash is a daunting task to any novice web developer. One can find help in a number of ways through books, friends and private tutors.</p>
                            </div>
                        </div>
                        <module type="btn" class="mt-3" text="Learn More" button_style="btn-primary" button_size=""/>
                    </div>
                </div>

                <div class="col-12 col-lg-8 col-xl-6 cloneable element safe-mode background-color-element p-3">
                    <div class="h-100 d-flex flex-column p-5" style="background-color: #ffffff;">
                        <div class="d-flex align-items-center justify-content-center mb-4 mx-xl-0 mx-auto cloneable element safe-mode background-color-element" style="background-color: rgba(253, 180, 0, 0.2); width: 70px; height: 70px; border-radius: 50%;">
                            <i class="icon-size-32px mw-micon-Add safe-element no-typing" style="color: #FDB400;"></i>
                        </div>
                        <div class="col-xl-10">
                            <div class="regular-mode">
                                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Data Security Guarantee</h5>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">Finding the perfect learning tool for Flash is a daunting task to any novice web developer. One can find help in a number of ways through books, friends and private tutors.</p>
                            </div>
                        </div>
                        <module type="btn" class="mt-3" text="Learn More" button_style="btn-primary" button_size=""/>
                    </div>
                </div>

                <div class="col-12 col-lg-8 col-xl-6 cloneable element safe-mode background-color-element p-3 ">
                    <div class="h-100 d-flex flex-column p-5" style="background-color: #ffffff;">
                        <div class="d-flex align-items-center justify-content-center mb-4 mx-xl-0 mx-auto cloneable element safe-mode background-color-element" style="background-color: rgba(0, 0, 0, 0.2); width: 70px; height: 70px; border-radius: 50%;">
                            <i class="icon-size-32px mw-micon-Add-UserStar safe-element no-typing" style="color: #000000;"></i>
                        </div>
                        <div class="col-xl-10">
                            <div class="regular-mode">
                                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">More Flexible Pricing</h5>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">Finding the perfect learning tool for Flash is a daunting task to any novice web developer. One can find help in a number of ways through books, friends and private tutors.</p>
                            </div>
                        </div>
                        <module type="btn" class="mt-3" text="Learn More" button_style="btn-primary" button_size=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
