


<template lang="">
    <div>
        <div>
                <slot/>
            <!-- <div class="">
                <Nav/>
                <loading class="custom-loader" v-model:active="isLoading" :can-cancel="true" :is-full-page="true" :loader="'bars'"  />
                <slot/>

                <Footer/>
            </div> -->
        </div>
    </div>
</template>
<script setup>
// import '/public/frontend_assets/js/common.js';

import Nav from './Nav.vue';
import Footer from './Footer.vue';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import { onMounted,ref,computed } from 'vue';
import { router,usePage } from '@inertiajs/vue3';

const isLoading = ref(true);
onMounted(() => {
    isLoading.value = false;
})

router.on('start', () => {
    isLoading.value = true;
    })
    router.on('finish', () => {
    isLoading.value = false;
    })
</script>


<style>
/*
    @import '/public/frontend_assets/css/all.min.css';
    @import '/public/frontend_assets/css/brands.min.css';
    @import '/public/frontend_assets/css/fontawesome.min.css';
    @import '/public/frontend_assets/css/regular.min.css';
    @import '/public/frontend_assets/css/solid.min.css';
    @import '/public/frontend_assets/css/slick.min.css';
    @import '/public/frontend_assets/css/slick-theme.min.css';
    @import '/public/frontend_assets/css/bootstrap.min.css';
    @import '/public/frontend_assets/style.css';
    */
</style>
