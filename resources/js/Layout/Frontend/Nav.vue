<template lang="">

<header class="main-head">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <Link :href="route('frontend.home')" class="navbar-brand"><img src="/frontend_assets/images/logo.svg" alt=""></Link>
                <!-- <a class="navbar-brand" href="/"></a> -->
                <button class="navbar-toggler navbar-toggler-main" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <!-- <span class="navbar-toggler-icon"></span> -->
                <span class="stick"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <button class="navbar-toggler navbar-toggler-main" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <!-- <span class="navbar-toggler-icon"></span> -->
                        <span class="stick"></span>
                    </button>
                    <ul class="navbar-nav ms-auto">
                        <li><a href="#url">Home</a></li>
                    </ul>
                    <template v-if="$page.props.isLogin && $page.props.isUser">

                        <div class="right-account-sec-wpr">
                            <div class="right-account-sec" @click ="isProfileShow = !isProfileShow"  :class="isProfileShow ? 'active': ''">
                                <i>
                                    <img :src="$page.props.auth.user.profile_photo" alt="">
                                </i>
                                <div class="account-name">
                                    <span class="full_name">{{ $page.props.auth.user.full_name }}</span>
                                    <!-- <span class="short_name">J D</span> -->
                                    <i>
                                        <img src="/frontend_assets/images/profile-drop.svg" alt="">
                                    </i>
                                </div>
                            </div>
                            <div class="profile-drop" :style="isProfileShow ? 'display:block;': 'display:none;'">
                                <ul>
                                    <li>
                                        <Link :href="route('frontend.profile')">Profile</Link>
                                    </li>
                                    <li class="logout-btn">
                                        <Link href="/logout" method="post" as="button">Logout</Link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </template>
                    <!-- <div v-else class="navbar_right">
                        <Link :href="route('frontend.login')" class="cmn_btn hdr_login_btn">Login</Link>
                    </div> -->
                </div>
            </nav>
        </div>

        <button class="navbar-toggler" id="navoverlay" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"></button>

    </header>



 <!--    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">

      <li class="nav-item active">

        <Link class="nav-link" href="/">Home</Link>

      </li>

      <li class="nav-item">
        <Link class="nav-link" href="/about">About</Link>
      </li>

      <li class="nav-item">
        <Link class="nav-link" href="/login">Login</Link>
      </li>

        <template v-if="$page.props.isLogin && $page.props.isUser">
            <li class="nav-item">
                <Link class="nav-link" href="/dashboard">Dashboard</Link>
            </li>

            <li class="nav-item">
                <Link class="nav-link" href="/profile">Profile</Link>
            </li>

            <li class="nav-item">
                <Link href="/logout" class="nav-link" method="post" as="button">Logout</Link>
            </li>
        </template>
    </ul>
  </div>
</nav> -->
</template>
<script setup>
import { onMounted,ref } from 'vue';

const isProfileShow = ref(true);
onMounted(() => {
    isProfileShow.value = false;
})


</script>

<style>
    .profile-drop {
    z-index:99 !important;
}

</style>
