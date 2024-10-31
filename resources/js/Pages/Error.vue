<script setup>
import { ref } from "vue";
import { computed } from "vue";

const routePath = ref("");

const props = defineProps({ status: Number });

const title = computed(() => {
    return {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden",
    }[props.status];
});

const description = computed(() => {
    return {
        503: "Sorry, we are doing some maintenance. Please check back soon.",
        500: "Whoops, something went wrong on our servers.",
        404: "Sorry, the page you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this page.",
    }[props.status];
});

const userRole = computed(() => usePage().props.isAdmin)
routePath.value = userRole ? 'admin.dashboard' : 'frontend.home'

</script>

<template>
    <div class="container">
    <div class="row">
        <div class="col-md-12 error-container">
            <div class="error-code">{{ props.status }}</div>
            <div class="error-message">{{ title }}</div>
            <p class="lead">{{ description }}</p>
            <Link :href="route(routePath)" class="back-to-home">Back to Home</Link>
        </div>
    </div>
</div>
<!--     <div
        class="d-flex flex-column align-items-center justify-content-center vh-100 bg-danger"
    >
        <h1 class="display-3 fw-bold text-white">{{ title }}</h1>
        <div class="display-5 fw-bold text-white">{{ description }}</div>
    </div> -->
</template>
<style scoped>
        body {
            background-color: #f8f9fa;
        }

        .error-container {
            text-align: center;
            padding: 50px 20px;
        }

        .error-code {
            font-size: 150px;
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 24px;
            color: #495057;
            margin-bottom: 20px;
        }

        .back-to-home {
            display: inline-block;
            padding: 10px 20px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
</style>
