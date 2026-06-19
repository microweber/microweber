{{--
type: layout
name: Team 11
position: 11
categories: Team
--}}

<style>
    #{{ $params['id'] }} {
        /* Additional styles can be added here */
    }
    .center-circle {
        border: 25px solid #fff;
        z-index: 1;
        border-radius: 100%;


    }

    .team-11-circle-wrapper {
        img {
            height: 100% !important;
            width: 100% !important;
        }
    }
</style>


<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section team-11-circle-wrapper"
    field-name="layout-team-skin-11"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center text-lg-start d-flex align-items-center justify-content-between">
                <div class="col-12 col-sm-10 col-lg-6 col-lg-5 mx-auto mx-lg-0">
                    <h6>Feedback Management</h6>
                    <p>One of the earliest activities we engaged in when we first got into astronomy is the same one we like to show our children just as soon as their excitement about the night sky begins.</p>
                </div>

                <div class="col-12 col-sm-10 col-lg-6 col-lg-6 mx-auto">
                    <div class="d-flex align-items-center position-relative">
                        <div class="w-175 mx-auto position-absolute left-0">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/1.jpg') }}" alt="" />
                            </div>
                        </div>

                        <div class="mx-auto center-circle">
                            <div class="w-250 mx-auto">
                                <div class="img-as-background rounded-circle square">
                                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/2.jpg') }}" alt="" />
                                </div>
                            </div>
                        </div>

                        <div class="w-175 mx-auto position-absolute right-0">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/3.jpg') }}" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
