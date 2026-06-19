{{--
 type: layout
 name: Feature 63
 position: 63
 categories: Features
--}}

<style>

    .feature-63 .icon-box {
        text-align: center;
        padding: 70px 20px 80px 20px;
        transition: all ease-in-out 0.3s;
        background: #fff;
        box-shadow: 0px 5px 90px 0px rgba(110, 123, 131, 0.05);
    }

    .feature-63 .icon-box .icon {
        margin: 0 auto;
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: ease-in-out 0.3s;
        position: relative;
    }

    .feature-63 .icon-box .icon i {
        font-size: 36px;
        transition: 0.5s;
        position: relative;
    }

    .feature-63 .icon-box .icon svg {
        position: absolute;
        top: 0;
        left: 0;
    }

    .feature-63 .icon-box .icon svg path {
        transition: 0.5s;
        fill: #f5f5f5;
    }

    .feature-63 .icon-box h4 {
        font-weight: 600;
        margin: 10px 0 15px 0;
        font-size: 22px;
    }

    .feature-63 .icon-box h4 a {
        color: #45505b;
        transition: ease-in-out 0.3s;
    }

    .feature-63 .icon-box p {
        line-height: 24px;
        font-size: 14px;
        margin-bottom: 0;
    }

    .feature-63 .icon-box:hover {
        border-color: #fff;
        box-shadow: 0px 0 35px 0 rgba(0, 0, 0, 0.08);
    }



    .feature-63 .icon-box:hover .icon i {
        color: var(--mw-primary-color);
    }


</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section feature-63"
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

        <div class="mw-layout-container container no-element edit" field="layout-feature-skin-63-{{ $params['id'] }}" rel="module">
            <div class="section-title text-center">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Services</h2>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>

            <x-row>
                <div class="col-lg-4 col-md-6 cloneable element d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                    <div class="icon-box background-color-element element">
                        <div class="icon">
                            <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174"></path>
                            </svg>
                            <i class="mw-micon-Basket-Ball"></i>
                        </div>
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}"><a href="">Lorem Ipsum</a></h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 cloneable element d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
                    <div class="icon-box background-color-element element">
                        <div class="icon">
                            <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426"></path>
                            </svg>
                            <i class="mw-micon-Notepad"></i>
                        </div>
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}"><a href="">Sed Perspiciatis</a></h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 cloneable element d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
                    <div class="icon-box background-color-element element">
                        <div class="icon">
                            <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,566.797414625762C385.7384707136149,576.1784315230908,478.7894351017131,552.8928747891023,531.9192734346935,484.94944893311C584.6109503024035,417.5663521118492,582.489472248146,322.67544863468447,553.9536738515405,242.03673114598146C529.1557734026468,171.96086150256528,465.24506316201064,127.66468636344209,395.9583748389544,100.7403814666027C334.2173773831606,76.7482773500951,269.4350130405921,84.62216499799875,207.1952322260088,107.2889140133804C132.92018162631612,134.33871894543012,41.79353780512637,160.00259165414826,22.644507872594943,236.69541883565114C3.319112789854554,314.0945973066697,72.72355303640163,379.243833228382,124.04198916343866,439.3748458443577C170.7312796277747,491.8107796887764,230.57421082200815,532.3932930995766,300,566.797414625762"></path>
                            </svg>
                            <i class="mw-micon-Cloud-Computer"></i>
                        </div>
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}"><a href="">Dele Cardo</a></h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Quis consequatur saepe eligendi voluptatem consequatur dolor consequuntur</p>
                    </div>
                </div>

            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>
