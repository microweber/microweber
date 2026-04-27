<div class="mw-before-after" id="mw-before-after-{{ $id }}" data-loading="true">
    <style>
        #mw-before-after-{{ $id }} .twentytwenty-container {
            min-height: 100px !important;
        }
    </style>

    <img src="{{ $before }}" alt="{{ __('Before image') }}" class="img-fluid"/>
    <img src="{{ $after }}" alt="{{ __('After image') }}" class="img-fluid"/>
</div>

<style>
    #mw-before-after-{{ $id }}[data-loading="true"] img{
        opacity: 0
    }
    #mw-before-after-{{ $id }}[data-loading="true"]:after{
        top: 50%;
        left: 50%;
        transform: rotate(90deg) translate(-50%, -50%);
        width: 48px;
        height: 48px;
        position: absolute;
        color: #FFF;
        border: 1px solid;
        box-sizing: border-box;
        animation: mw-before-after-{{ $id }}fill 2s linear infinite alternate;
        content: '';
        transform-origin: left top;
    }

    @keyframes mw-before-after-{{ $id }}fill {
        0% {
            box-shadow: 0 0  inset;
        }
        100% {
            box-shadow: 0 -48px inset;
        }
    }


    #mw-before-after-{{ $id }}[data-loading="true"]{

        height: 150px;
        width: 100%;
        max-width: 550px;
        margin: auto;
        position: relative;
    }

</style>

<script>

    mw.moduleCSS('{{ asset('modules/before_after/css/twentytwenty.css') }}');
    mw.moduleJS('{{ asset('modules/before_after/js/jquery.event.move.js') }}');
    mw.moduleJS('{{ asset('modules/before_after/js/jquery.twentytwenty.js') }}');

    ;(function(){
        const imagePromises = ['{{ $before }}', '{{ $after }}'].map(url => new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = () => reject(img);
            img.src = url;
        }));
        Promise.allSettled(imagePromises)
            .then((results) => {
                const images = results.map(r => r.value);
                const container = mw.$("#mw-before-after-{{ $id }}");
                container
                    .attr('data-loading', 'false')
                    .find('img')
                    .remove()
                container.append(images);
                setTimeout(() => container.twentytwenty({default_offset_pct: 0.5}));
            });
    })();

</script>
