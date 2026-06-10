@php
    /*

    type: layout

    name: Content 90

    position: 90

    categories: Content

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = '';
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = '';
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .content-90 {
        .about-img {
            min-height: 500px;
        }

        h3 {
            font-weight: 700;
            margin-bottom: 30px;
        }

        .call-us {
            left: 10%;
            right: 10%;
            bottom: 10%;
            background-color: #fff;
            box-shadow: 0px 2px 25px rgba(0, 0, 0, 0.08);
            padding: 20px;
            text-align: center;
        }

        .call-us h4 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .call-us p {
            font-weight: 700;
            color: var(--mw-primary-color);
        }

        .content ul {
            list-style: none;
            padding: 0;
        }

        .content ul li {
            padding: 0 0 8px 26px;
            position: relative;
        }

        .content ul i {
            position: absolute;
            left: 0;
            top: -3px;
            color: var(--mw-primary-color);
        }

        .content p:last-child {
            margin-bottom: 0;
        }

        .play-btn {
            width: 94px;
            height: 94px;
            background: radial-gradient(var(--mw-primary-color) 50%, rgba(206, 18, 18, 0.4) 52%);
            border-radius: 50%;
            display: block;
            position: absolute;
            left: calc(50% - 47px);
            top: calc(50% - 47px);
            overflow: hidden;
        }

        .play-btn:before {
            content: "";
            position: absolute;
            width: 120px;
            height: 120px;
            animation-delay: 0s;
            animation: pulsate-btn 2s;
            animation-direction: forwards;
            animation-iteration-count: infinite;
            animation-timing-function: steps;
            opacity: 1;
            border-radius: 50%;
            border: 5px solid rgba(206, 18, 18, 0.7);
            top: -15%;
            left: -15%;
            background: rgba(198, 16, 0, 0);
        }

        .play-btn:after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translateX(-40%) translateY(-50%);
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-left: 15px solid #fff;
            z-index: 100;
            transition: all 400ms cubic-bezier(0.55, 0.055, 0.675, 0.19);
        }

        .play-btn:hover:before {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translateX(-40%) translateY(-50%);
            width: 0;
            height: 0;
            border: none;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-left: 15px solid #fff;
            z-index: 200;
            animation: none;
            border-radius: 0;
        }

        .play-btn:hover:after {
            border-left: 15px solid var(--mw-primary-color);
            transform: scale(20);
        }

        @keyframes pulsate-btn {
            0% {
                transform: scale(0.6, 0.6);
                opacity: 1;
            }

            100% {
                transform: scale(1, 1);
                opacity: 0;
            }
        }
    }
</style>

<section class="section content-90">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container mw-layout-container safe-mode mw-layout-overlay-container {{ $layout_classes }} edit" field="layout-content-skin-90-{{ $params['id'] }}" rel="module">
        <div class="text-center mb-5">
            <p>About Us</p>
            <h2>Learn More <span>About Us</span></h2>
        </div>

        <div class="row gy-4">
            <div class="col-lg-7 position-relative about-img background-image-holder" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}');" data-aos="fade-up" data-aos-delay="150">
                <div class="call-us position-absolute">
                    <h4>Book a Table</h4>
                    <h2>+1 5589 55488 55</h2>
                </div>
            </div>
            <div class="col-lg-5 d-flex align-items-end" data-aos="fade-up" data-aos-delay="300">
                <div class="content ps-0 ps-lg-5">
                    <p class="fst-italic">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </p>
                    <ul>
                        <li><i class="mdi mdi-check"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
                        <li><i class="mdi mdi-check"></i> Duis aute irure dolor in reprehenderit in voluptate velit.</li>
                        <li><i class="mdi mdi-check"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</li>
                    </ul>
                    <p>
                        Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.
                    </p>

                    <div class="position-relative mt-4">
                        <img loading="lazy" class="img-fluid" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt="" />
                        <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox play-btn"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
