{{--
type: layout
name: Design 21
position: 121
categories: Design
--}}

<style>
    .mw-new-21-display-1 {
        font-weight: 700;
        margin-bottom: 0;
        text-align: center;
    }

    .mw-new-21-top-text {
        border: 2px solid #e1e1e1;
        border-radius: 40px;
        color: rgba(0, 0, 0, .7);
        display: inline-block;
        flex: 0 0 auto;
        font-size: 13px;
        font-weight: 700;
        padding: 7px 18px 6px;
        text-transform: uppercase;
    }

    .mw-new-21-section-cta {
        padding-left: 5%;
        padding-right: 5%;
        position: relative;
        cursor: none;
    }

    .mw-new-21-project-circle {
        align-items: center;
        border-radius: 100%;
        display: flex;
        justify-content: center;
        position: absolute;
        z-index: 10;
    }

    .mw-new-21-max-w-width-cta {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    @media screen and (max-width: 767px) {
        .mw-new-21-project-circle {
            display: none;
        }
    }

    .mw-new-21-display-1.mw-new-21-cta-title {
        font-size: 120px;
        line-height: 1;
        margin-top: 15px;
    }

    .mw-new-21-max-w-width-cta.center {
        max-width: 90%;
    }

    @media screen and (max-width: 991px) {
        .mw-new-21-display-1.mw-new-21-cta-title {
            line-height: 1.1;
        }

        .mw-new-21-section-cta {
            padding-bottom: 60px;
            padding-top: 60px;
        }

        .mw-new-21-project-circle.black {
            height: 110px;
            width: 110px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-21-display-1.mw-new-21-cta-title {
            font-size: 100px;
        }

        .mw-new-21-project-circle.black {
            display: flex;
        }
    }

    @media screen and (max-width: 579px) {
        .mw-new-21-display-1.mw-new-21-cta-title {
            font-size: 50px;
        }
    }

    .mw-new-21-project-circle.black {
        background-color: #000;
        cursor: none;
        height: 120px;
        width: 120px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.25s, transform 0.25s ease-in-out;
        transform: scale(0.5);
    }

    .mw-new-21-project-circle.black.zoomIn {
        opacity: 1;
        transform: scale(1);
    }

    .mw-new-21-project-circle.black svg {
        fill: #fff;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-21 mw-new-21-section-cta"
    field-name="layout-new-layouts-skin-21"
    container-class="mw-layout-container no-element edit safe-mode"
    default-padding-top="p-t-130"
    default-padding-bottom="p-b-150"
>
    <div class="mw-new-21-max-w-width-cta center">
        <div class="mw-new-21-top-text background-color-element element">let's talk</div>
        <div>
            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-21-display-1 mw-new-21-cta-title">Let's Talk</h3>
        </div>
    </div>
</x-layout-section>

<script>
    $(document).ready(function() {
        var section = document.querySelector('#layout-section--{{ $params['id'] }}');
        var projectCircle = document.querySelector('#layout-circle--{{ $params['id'] }}');

        if(!projectCircle) {
            projectCircle = mw.element(`
                <span class="mw-new-21-project-circle black" id="layout-circle--{{ $params['id'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="m226-559 78 33q14-28 29-54t33-52l-56-11-84 84Zm142 83 114 113q42-16 90-49t90-75q70-70 109.5-155.5T806-800q-72-5-158 34.5T492-656q-42 42-75 90t-49 90Zm178-65q-23-23-23-56.5t23-56.5q23-23 57-23t57 23q23 23 23 56.5T660-541q-23 23-57 23t-57-23Zm19 321 84-84-11-56q-26 18-52 32.5T532-299l33 79Zm313-653q19 121-23.5 235.5T708-419l20 99q4 20-2 39t-20 33L538-80l-84-197-171-171-197-84 167-168q14-14 33.5-20t39.5-2l99 20q104-104 218-147t235-24ZM157-321q35-35 85.5-35.5T328-322q35 35 34.5 85.5T327-151q-25 25-83.5 43T82-76q14-103 32-161.5t43-83.5Zm57 56q-10 10-20 36.5T180-175q27-4 53.5-13.5T270-208q12-12 13-29t-11-29q-12-12-29-11.5T214-265Z"/></svg>
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
