class SliderV2 {

    constructor(element, config) {
        this.element = element;
        this.config = config;
        this.driver = 'swiper';
        this.driverInstance = null;
        this.init();
    }

    init() {
        if (this.driver == 'swiper') {
            this.runSwiper();
        } else {
            console.log('SliderV2: driver not found');
        }

        if (self != top) {
            // mw.top().app.on('onItemChanged', (item) => {
            //     this.switchToSlideByItemId(item.itemId);
            // });
            // mw.top().app.on('editItemById', (itemId) => {
            //     this.switchToSlideByItemId(itemId);
            // });
        }
    }

    switchToSlideByItemId(itemId) {
        let slideIndex = Object.keys(this.config.slidesIndexes).findIndex((itemValue) => {

            if (itemValue == itemId) {

                 return true;
            }
        });

        if (typeof (slideIndex) != 'undefined') {
            this.slideTo(slideIndex);
        }
    }

    slideTo(index) {
        if (this.driver == 'swiper') {
            if(this.driverInstance && this.driverInstance.slideTo) {
                this.driverInstance.slideTo(index);
            }
        }
    }

    runSwiper()
    {
        mw.lib.require('swiper');
        console.log("module", mw.settings);

        let swiperConfig = {};
        if (this.config.loop) {
            swiperConfig.loop = true;
        }
        if (this.config.autoplay) {
            swiperConfig.autoplay = true;
        }

        if (this.config.delay) {
        //    swiperConfig.delay = this.config.delay;
            swiperConfig.autoplay = {
                delay: this.config.delay,
            };

        }
        if (this.config.pagination.element) {
            swiperConfig.pagination = {
                el: this.config.pagination.element,
                clickable: true
            };
        }
        if (this.config.direction) {
            swiperConfig.direction = this.config.direction;
        }

        // if (this.config.initialSlide) {
        //     swiperConfig.initialSlide = this.config.initialSlide;
        // }

        swiperConfig.navigation = {};
        if (this.config.navigation.nextElement) {
            swiperConfig.navigation.nextEl = this.config.navigation.nextElement;
        }
        if (this.config.navigation.previousElement) {
            swiperConfig.navigation.prevEl = this.config.navigation.previousElement;
        }

        this.driverInstance = new Swiper(this.element, swiperConfig);
    }
}



/*<style>
    #js-slider-<?php echo $params['id']; ?>{
        max-width: 100vw !important;
    }
</style>
<script>
    mw.require('<?php print modules_url(); ?>slider_v2/slider-v2.js');
    $(document).ready(function () {
        if(typeof sliderV2<?php echo $moduleHash; ?>_initialSlide === 'undefined'){
            window.sliderV2<?php echo $moduleHash; ?>_initialSlide = <?php echo $currentSlide; ?>;
        }


       window.sliderV2<?php echo $moduleHash; ?> = null;

       window.sliderV2<?php echo $moduleHash; ?> = new SliderV2('#js-slider-<?php echo $params['id']; ?>', {
            loop: true,

           <?php if($isAutoSlideEnabled): ?>

            autoplay:true,
            <?php endif; ?>
           <?php if($slideInterval): ?>

           delay: <?php echo intval($slideInterval); ?>,

           <?php endif; ?>

            pagination: {
                element: '#js-slide-pagination-<?php echo $params['id']; ?>',
            },
            navigation: {
                nextElement: '#js-slide-pagination-next-<?php echo $params['id']; ?>',
                previousElement: '#js-slide-pagination-previous-<?php echo $params['id']; ?>',
            },
            slidesIndexes: <?php echo json_encode($slidesIndexes); ?>,
            initialSlide: window.sliderV2<?php echo $moduleHash; ?>_initialSlide,
        });
    });
</script>
*/
