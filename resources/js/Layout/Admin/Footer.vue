<template lang="">
   <div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
    <div class="kt-footer__copyright">
       {{new Date().getFullYear()}} &nbsp;&copy;&nbsp;<a href="/admin/dashboard"
            class="kt-link">{{usePage().props.appName }}</a>
    </div>

    <div class="kt-footer__menu">
       <!-- <a href="#" class="kt-footer__menu-link kt-link">About</a>
        <a href="#" class="kt-footer__menu-link kt-link">Team</a>
        <a href="#" class="kt-footer__menu-link kt-link">Contact</a> -->
    </div>
</div>
</template>

<script setup>
  import { usePage } from '@inertiajs/vue3'
  console.log(usePage,"usePage");
</script>
<style lang="">

</style>
