<template>
    <Carousel class="news-slider" ref="carousel" v-bind="settings" :breakpoints="breakpoints"
    :autoplay="autoplayInterval"
    :wrap-around="true"
    @mouseover="pauseAutoplay"
    @mouseout="resumeAutoplay"
    >
        <Slide v-for="blog in blogs" :key="blog.id">
            <div class="news-slider-item">
                <BlogCard :blog="blog"/>
            </div>
        </Slide>
    </Carousel>
    <div class="cust-arr">
        <ul>
            <li>
                <button class="arr arr-lft arr-abs" @click="prev">
                    <img src="frontend_assets/images/arr-lft.svg" alt="">
                </button>
            </li>
            <li>
                <button class="arr arr-rt arr-abs" @click="next">
                    <img src="frontend_assets/images/arr-rt.svg" alt="">
                </button>
            </li>
        </ul>
    </div>
  </template>

  <script setup>
    import { ref } from 'vue';
    import { Carousel, Slide } from 'vue3-carousel';
    import 'vue3-carousel/dist/carousel.css';
    import BlogCard from '@/components/frontend/BlogCard.vue';

    const carousel = ref(null);

    const {blogs} = defineProps({blogs:Object});

    const autoplayInterval = ref(2000);

    const settings = {
        itemsToShow: 1,
        snapAlign: 'center',
    };

    const breakpoints = {
        700: {
                itemsToShow: 2,
                snapAlign: 'center',
        },
        1024: {
                itemsToShow: 3,
                snapAlign: 'start',
        },
    };

    const next = () => {
        console.log('next');
        carousel.value.next();
    };

    const prev = () => {
        carousel.value.prev();
    };

    const pauseAutoplay = () => {
      autoplayInterval.value = false;
    };

    const resumeAutoplay = () => {
      autoplayInterval.value = 2000;
    };
</script>
