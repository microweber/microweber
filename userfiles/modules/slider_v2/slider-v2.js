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
            this.driverInstance.slideTo(index);
        }
    }

    runSwiper()
    {
        mw.lib.require('swiper');

        let swiperConfig = {};
        if (this.config.loop) {
            swiperConfig.loop = true;
        }
        if (this.config.autoplay) {
            swiperConfig.autoplay = true;
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
