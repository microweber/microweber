if(!window.SliderV2) {



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



            swiperConfig.navigation = {};

            if (this.config.navigation) {
                const next = this.config.navigation.nextElement || this.config.navigation.nextEl;
                const prev = this.config.navigation.prevElement || this.config.navigation.prevEl || this.config.navigation.previousElement || this.config.navigation.previousEl;
                if (next) {
                    swiperConfig.navigation.nextEl = next;
                }
                if (prev) {
                    swiperConfig.navigation.prevEl = prev;
                }
            }


            this.driverInstance = new Swiper(this.element, swiperConfig);
        }
    }

    window.SliderV2 = SliderV2;

    }
