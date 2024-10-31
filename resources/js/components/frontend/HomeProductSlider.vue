<template>

<div class="deals_slider_btn_wrap custom-controls" >
        <button class="arrw_prev arr" @click="prev">
            <img src="frontend_assets/images/arr-lft.svg" alt="">
        </button>
        <button class="arrw_next arr" @click="next">
            <img src="frontend_assets/images/arr-rt.svg" alt="">
        </button>
    </div>

    <Carousel class="news-slider carousel-container" ref="carousel"
            v-bind="settings" :breakpoints="breakpoints" :wrap-around="true" :autoplay="autoplayInterval"
            @mouseover="pauseAutoplay"
            @mouseout="resumeAutoplay"
            >
        <Slide v-for="slide in 10" :key="slide">
            <div class="deal_slider_item">
                <div class="deal_slider_box">
                    <a href="#url" class="deal_slider_img">
                        <img src="frontend_assets/images/latest-deals-item2.png" alt="">
                    </a>
                    <h3><a href="#url">Samsung galaxy</a></h3>
                    <ul>
                        <li>4gb data</li>
                        <li>No upfront cost</li>
                    </ul>
                    <a href="#url" class="cmn_btn">See deals</a>
                </div>
            </div>
        </Slide>
    </Carousel>
  </template>

  <script>

  import { defineComponent } from 'vue';
  import { Carousel, Navigation, Slide } from 'vue3-carousel';
  // Import the carousel styles
  import 'vue3-carousel/dist/carousel.css'

  export default defineComponent({
    name: 'HomeProductSlider',
    components: {
      Carousel,
      Slide,
    },
    data: () => ({
        autoplayInterval:2000,
        // carousel settings
        settings: {
        itemsToShow: 1,
        snapAlign: 'center',
        },
        // breakpoints are mobile first
        // any settings not specified will fallback to the carousel settings
        breakpoints: {
        // 700px and up
        480: {
            itemsToShow: 2,
            snapAlign: 'start',
        },
        // 1024 and up
        992: {
            itemsToShow: 3,
        },
        },
    }),
    methods: {
        next() {
            console.log("next");
            this.$refs.carousel.next()
        },
        prev() {
            this.$refs.carousel.prev()
        },
        pauseAutoplay() {
            this.autoplayInterval = false;
        },
        resumeAutoplay() {
            this.autoplayInterval = 2000;
        },
    },
  })
  </script>

<style scoped>
.deal_slider_item{
    width: 100%;
}

.carousel-container {
  position: relative;
}
.deal_slider .custom-controls {
  position: absolute;
  top: 0;
  right: 0;
  display: flex;
  align-items: center;
}

.deal_slider .custom-controls .arrw_next{
    margin-left: 30px;
}


@media(max-width:767px){

}


</style>
