<template lang="">


<!-- begin:: Aside -->
<button class="kt-aside-close " v-if="mobileMenuHideShow" @click="mobileMenuHideShow = !mobileMenuHideShow" id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" :class="{'kt-aside--on': mobileMenuHideShow}" id="kt_aside" >
    <!-- begin:: Aside -->
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand" style="background-color: aliceblue;">
        <div class="kt-aside__brand-logo">
            <Link :href="route('admin.dashboard')" class="logo_text_custom">
                 <!-- <span>Bluelightcompare</span> -->
                 <img src="/admin_assets/logo/logo.png" alt="" style="width:90px;">
                <!-- <img :src="$page.props.logo" alt="" width="100%"
                    style="padding: 10px; width:100%;"> -->
            </Link>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler" @click="toggleSideMenu">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero"
                                transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                            <path
                                d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                        </g>
                    </svg>
                </span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero" />
                            <path
                                d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                        </g>
                    </svg>
                </span>
            </button>
        </div>
    </div>

    <!-- end:: Aside -->

    <!-- begin:: Aside Menu -->
           <perfect-scrollbar>
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
            data-ktmenu-dropdown-timeout="500">

            <ul class="kt-menu__nav ">
               <li class="kt-menu__item  " :class="{ 'kt-menu__item--active': $page.url === '/admin/dashboard' }"
                    aria-haspopup="true">
                        <Link :href="route('admin.dashboard')" class="kt-menu__link ">
                       <Icon icon="material-symbols:dashboard-outline" color="white" width="20" height="20" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">Dashboard</span>
                        </Link>
                </li>

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">User Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">

                    </i>
                </li>

                <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/user') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.users')" class="kt-menu__link" >
                        <Icon icon="fa-solid:users" color="white" width="20" height="20" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text" >Users </span>
                    </Link>
                </li>

                <!-- <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Master Data Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">

                    </i>
                </li> -->


               <!--  <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/newsletter') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.newsletterData.list')" class="kt-menu__link" >
                                                <Icon icon="mdi:email-newsletter" color="white" width="20" height="20" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text" >Newsletter Data </span>
                    </Link>
                </li> -->

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Group Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>

                <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/GroupType') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.groupTypes')" class="kt-menu__link" >
                        <Icon icon="fa6-solid:list" color="white" width="20" height="20" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text" >Group Types </span>
                    </Link>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Group') && !$page.component.startsWith('Admin/GroupType') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.groups')" class="kt-menu__link" >
                       <Icon icon="lets-icons:group-light" color="white" width="30" height="30" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">Groups</span>
                    </Link>
                </li>

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Event Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>

                <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/EventType') && !$page.component.startsWith('Admin/event') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.eventTypes')" class="kt-menu__link" >
                        <Icon icon="fa6-solid:list" color="white" width="20" height="20" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text" >Event Type </span>
                    </Link>
                </li>

                <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/event') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.events')" class="kt-menu__link" >
                        <Icon icon="mdi:event-range-outline" color="white" width="25" height="25" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text" >Events </span>
                    </Link>
                </li>

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Post Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Post-status') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.post-status')" class="kt-menu__link" >
                       <Icon icon="system-uicons:paper-plane" color="white" width="26" height="26" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">Posts</span>
                    </Link>
                </li>

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Master Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>


                <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Strategy') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.strategies')" class="kt-menu__link" >
                        <Icon icon="ic:outline-event-note" color="white" width="20" height="20" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text" >Strategy </span>
                    </Link>
                </li>

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Report Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/ReportType') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.reportTypes')" class="kt-menu__link" >
                       <Icon icon="fluent:content-view-20-regular" color="white" width="26" height="26" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">Report Type</span>
                    </Link>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Report') && !$page.component.startsWith('Admin/ReportType') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.reports')" class="kt-menu__link" >
                       <Icon icon="material-symbols-light:flag-outline" color="white" width="28" height="28" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">Reports</span>
                    </Link>
                </li>

                 <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Content Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/cms') }"
                    aria-haspopup="true">
                    <Link :href="route('admin.cms.index')" class="kt-menu__link" >
                       <Icon icon="fluent:content-view-20-regular" color="white" width="26" height="26" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">CMS Pages</span>
                    </Link>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/faq') }"
                    aria-haspopup="true">
                   <Link :href="route('admin.faq.index')" class="kt-menu__link " >
                        <Icon icon="wpf:faq"  color="white" width="24" height="24" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">FAQ</span>
                    </Link>
                </li>

                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Feedback Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2">
                    </i>
                </li>

                <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Support') }"
                    aria-haspopup="true">
                   <Link :href="route('admin.supports')" class="kt-menu__link " >
                        <Icon icon="material-symbols-light:support-agent-outline-rounded"  color="white" width="24" height="24" class="kt-menu__link-icon mr-3"/>
                        <span class="kt-menu__link-text">Support Ticket</span>
                    </Link>
                </li>

            </ul>
        </div>
    </div>
    </perfect-scrollbar>
    <!-- end:: Aside Menu -->
</div>

<!-- end:: Aside -->



<!-- <div v-if="mobileMenuHideShow" class="kt-aside-overlay"></div> -->






    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Admin</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">

      <li class="nav-item active">

        <Link class="nav-link" href="/home">Home</Link>

      </li>

      <li class="nav-item">
        <Link class="nav-link" href="/about">About</Link>
      </li>

      <li class="nav-item">
        <Link href="/admin/logout" method="post" as="button" type="button">Logout</Link>
      </li>

    </ul>
  </div>
</nav> -->
</template>
<script setup>
// import { useForm } from '@inertiajs/vue3'

import { ref } from "vue";

const hideShow = ref(false);
const mobileMenuHideShow = ref(false);


const toggleSideMenu = () => {
    hideShow.value = !hideShow.value;
     emit.emit('toggleSideMenu', hideShow.value);
}

emit.on('toggleMobileMenu', function (arg1) {
        mobileMenuHideShow.value = arg1;
    });

</script>

<style>
    /* .kt-aside {
    background-color: v-bind($page.props.buttonColor) !important;
    } */


</style>
