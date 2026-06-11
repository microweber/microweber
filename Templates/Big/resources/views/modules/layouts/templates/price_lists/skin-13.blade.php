{{--
type: layout

name: Price Lists 13

position: 13

categories: Price Lists
--}}

<style>
    .pricing-thumb {
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .pricing-title-wrap,
    .pricing-body {
        padding: 40px;
    }

    .pricing-body i {
        font-size: 20px;
    }

    .pricing-title-wrap {
        background-color: var(--mw-primary-color);
        padding: 20px 40px;
    }

    .pricing-title {
        color: #fff;
    }

    .pricing-body p {
        display: flex;
        align-items: center;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section price-list-12"
    field-name="layout-skin-13"
    container-class="mw-layout-container allow-drop edit safe-mode"
>
    <x-row>
                <div class="col-lg-10 col-12 text-center mx-auto mb-5 allow-select">
                    <h2>Get Your <u class="text-info">Tickets</u></h2>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-5 mb-lg-0 cloneable element allow-select ">
                    <div class="pricing-thumb bg-white shadow-lg">
                        <div class="pricing-title-wrap background-color-element element d-flex align-items-center">
                            <h4 class="pricing-title text-white mb-0">Early Bird</h4>
                            <h5 class="pricing-small-title text-white mb-0 ms-auto">$640</h5>
                        </div>

                        <div class="pricing-body safe-mode">
                            <p>
                                <i class="me-2 mw-micon-Coffee-toGo"></i> All-Day Coffee + Snacks
                            </p>
                            <p>
                                <i class="me-2 mw-micon-Video-GameController"></i> After Party
                            </p>
                            <p>
                                <i class="me-2 mw-micon-Support"></i> 24/7 Support
                            </p>
                            <div class="border-bottom pb-3 mb-4"></div>
                            <p>Quick group meetings for multiple teams</p>
                            <module class="safe-element mt-4" type="btn" button_text="Buy Tickets" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-5 mb-lg-0 cloneable element allow-select ">
                    <div class="pricing-thumb bg-white shadow-lg">
                        <div class="pricing-title-wrap background-color-element element d-flex align-items-center">
                            <h4 class="pricing-title text-white mb-0">Gold</h4>
                            <h5 class="pricing-small-title text-white mb-0 ms-auto">$840</h5>
                        </div>

                        <div class="pricing-body safe-mode">
                            <p>
                                <i class="me-2 mw-micon-Coffee-toGo"></i> All-Day Coffee + Snacks
                            </p>
                            <p>
                                <i class="me-2 mw-micon-People-onCloud"></i> Group Meetings + After Party
                            </p>
                            <p>
                                <i class="me-2 mw-micon-Support"></i> 24/7 Support + Instant Chats
                            </p>
                            <div class="border-bottom pb-3 mb-4"></div>
                            <p>Quick group meetings for multiple teams</p>
                            <module class="safe-element mt-4" type="btn" button_text="Buy Tickets" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 cloneable element allow-select ">
                    <div class="pricing-thumb bg-white shadow-lg">
                        <div class="pricing-title-wrap background-color-element element d-flex align-items-center">
                            <h4 class="pricing-title text-white mb-0">Platinum</h4>
                            <h5 class="pricing-small-title text-white mb-0 ms-auto">$1,240</h5>
                        </div>

                        <div class="pricing-body safe-mode">
                            <p>
                                <i class="me-2 mw-micon-Money"></i> Cashback $200
                            </p>
                            <p>
                                <i class="me-2 mw-micon-People-onCloud"></i> Private Meetings + After Party
                            </p>
                            <p>
                                <i class="me-2 mw-micon-Support"></i> 24/7 Support + Instant Chats
                            </p>
                            <div class="border-bottom pb-3 mb-4"></div>
                            <p>group talks and private chats for multiple teams</p>
                            <module class="safe-element mt-4" type="btn" button_text="Buy Tickets" />
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
