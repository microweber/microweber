{{--
type: layout
name: Design 22
position: 122
categories: Design
--}}

<style>
    .mw-new-22-display-1 {
        font-weight: 700;
        margin-bottom: 0;
        text-align: center;
    }

    .mw-new-22-section-cta {
        padding-left: 5%;
        padding-right: 5%;
        position: relative;
        cursor: none;
    }

    .mw-new-22-project-circle {
        align-items: center;
        border-radius: 100%;
        display: flex;
        justify-content: center;
        position: absolute;
        z-index: 10;
    }

    .mw-new-22-max-w-width-cta {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    @media screen and (max-width: 767px) {
        .mw-new-22-project-circle {
            display: none;
        }
    }

    .mw-new-22-display-1.mw-new-22-cta-title {
        font-size: 120px;
        line-height: 1;
        margin-top: 15px;
    }

    .mw-new-22-max-w-width-cta.center {
        max-width: 90%;
    }

    @media screen and (max-width: 991px) {
        .mw-new-22-display-1.mw-new-22-cta-title {
            line-height: 1.1;
        }

        .mw-new-22-section-cta {
            padding-bottom: 60px;
            padding-top: 60px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-22-display-1.mw-new-22-cta-title {
            font-size: 100px;
        }

        .mw-new-22-project-circle.purple {
            display: flex;
        }
    }

    @media screen and (max-width: 580px) {
        .mw-new-22-display-1.mw-new-22-cta-title {
            font-size: 50px;
        }
    }

    .mw-new-22-project-circle.purple {
        background-color: var(--mw-primary-color);
        cursor: none;
        height: 85px;
        width: 85px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s, transform 0.25s ease-in-out;
        transform: scale(0.5);
    }

    .mw-new-22-project-circle.purple.zoomIn {
        opacity: 1;
        transform: scale(1);
    }

    .mw-new-22-project-circle.purple svg {
        transform: rotate(-45deg);
        pointer-events: none;
        fill: #fff;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-22 mw-new-22-section-cta"
    field-name="layout-new-layouts-skin-22"
    container-class="mw-layout-container no-element edit safe-mode"
    default-padding-top="p-t-100"
    default-padding-bottom="p-b-100"
>
    <div class="mw-new-22-max-w-width-cta center">
        <div>
            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-22-display-1 mw-new-22-cta-title">CONTACT US</h3>
        </div>
    </div>
</x-layout-section>

<script>
    $(document).ready(function() {
        var section = document.querySelector('#layout-section--{{ $params['id'] }}');
        var projectCircle = document.querySelector('#layout-circle--{{ $params['id'] }}');

        if(!projectCircle) {
            projectCircle = mw.element(`
                <span class="mw-new-22-project-circle purple" id="layout-circle--{{ $params['id'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="m560-240-56-58 142-142H160v-80h486L504-662l56-58 240 240-240 240Z"/></svg>
                </span>
            `).get(0)
            document.body.appendChild(projectCircle);
        }

        section.addEventListener('mousemove', function(e) {
            projectCircle.style.left = e.pageX - projectCircle.offsetWidth/2 + 'px';
            projectCircle.style.top = e.pageY - projectCircle.offsetHeight/2 + 'px';
            projectCircle.classList.add('zoomIn');
        });

        section.addEventListener('mouseleave', function(e){
            projectCircle.classList.remove('zoomIn');
        });
    });
</script>
