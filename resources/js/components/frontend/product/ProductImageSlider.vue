<template>
    <div>

        <Carousel class="handset_img_slider_item" :autoplay="2000" :wrap-around="true">
            <Slide v-for="image in 3" :key="image">
                    <span class="pagingInfo handset_img_count">{{image}}/3</span>
                <figure class="handset_img">
                    <img src="frontend_assets/images/product-img1.jpg" />
                </figure>
            </Slide>
        </Carousel>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Carousel, Slide } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

const {images} = defineProps({images:Object})
const carousel = ref(null);
</script>
