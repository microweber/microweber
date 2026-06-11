{{--
type: layout
name: Team 17
position: 17
categories: Team
--}}

<style>
    .team-17-btn {
        border-radius: 100px 0 100px 100px!important;
        background-color: #FF2359;
        color: white;
        padding: 20px 40px;
    }

    .flower-team-card-img {
        border-radius: 0 15px 0 15px;
        padding: 10px;
    }

    .flower-card.card {
        margin: 0 20px;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    container-class="mw-layout-container no-element container-fluid"
>
    <x-row>
                <div class="col-xl-10 col-md-12 mx-auto">
                    <div class="d-flex justify-content-center flex-wrap text-center mx-auto edit" field="layout-team-skin-17-{{ $params['id'] }}" rel="module">
                        <div class="col-12 col-md-6 col-12 mx-auto">
                            <div class="text-center text-lg-center pb-5 position-relative">
                                <h2 class="text-xl-start mb-0">Meet Our <br> Professional Mentor</h2>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-12 mx-auto text-center text-xl-start d-flex align-items-center justify-content-center">
                            <div>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt amet sit placerat diam praesent pharetra at. Gravida ornare mauris pretium tortor, ac in nulla eleifend.</p>
                                <br/>
                                <module type="btn" button_text="View All" button_style="team-17-btn"/>
                            </div>
                        </div>
                    </div>
                    <module type="teamcard" template="skin-15" />
                </div>
            </x-row>
</x-layout-section>
