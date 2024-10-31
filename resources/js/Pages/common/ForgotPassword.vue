<template lang="">
    <div class="col-md-4 offset-md-4" style="margin-top:90px;">
        <div  class="d-flex justify-content-center">
            <img :src="$page.props.logo" alt="logo" style="margin-botton:10px;">
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Forgot Password</h4>
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label for="email">Email<span class=text-danger>*</span></label>
                        <input type="email" id="email" v-model="form.email" class="form-control" placeholder="Email" >
                        <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary"  :disabled="form.processing">
                        <div :class="{'spinner-border':form.processing, 'spinner-border-sm':form.processing}" role="status"></div>
                        Submit</button>
                </form>
                <div>
                    <Link :href="route('admin.login')" class="float-right">Sign In</Link>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { reactive, ref } from 'vue'
import { useForm, router, usePage} from '@inertiajs/vue3'
import { computed } from 'vue';


const mainColor = computed(() => usePage().props.theme.mainColor)
const hoverColor = computed(() => usePage().props.theme.hoverColor)
const buttonColor = computed(() => usePage().props.theme.buttonColor)

const form = useForm({
  email: '',
})

defineProps({
  errors:Object
})

function submit() {
  form.post(route('frontend.forgotPassword'))
}
</script>
<style scoped>

@import '/public/admin_assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.min.rtl.css';
@import '/public/admin_assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.min.css';
@import '/public/admin_assets/vendors/general/socicon/css/socicon.css';
@import '/public/admin_assets/vendors/custom/vendors/line-awesome/css/line-awesome.css';
@import '/public/admin_assets/vendors/custom/vendors/flaticon/flaticon.css';
@import '/public/admin_assets/vendors/custom/vendors/flaticon2/flaticon.css';
@import '/public/admin_assets/vendors/custom/vendors/fontawesome5/css/all.min.css';
@import '/public/admin_assets/demo/default/base/style.bundle.min.css';
@import '/public/admin_assets/demo/default/skins/header/base/light.css';
@import '/public/admin_assets/demo/default/skins/header/menu/light.css';
@import '/public/admin_assets/demo/default/skins/brand/dark.css';
@import '/public/admin_assets/demo/default/skins/aside/dark.css';
@import '/public/admin_assets/vendors/custom/datatables/datatables.bundle.css';

.btn-primary {
    color: #fff;
    background-color: v-bind(mainColor) !important;
    border-color: v-bind(mainColor);
}

.btn-primary:hover {
    border-color: v-bind(hoverColor);
}

.btn-primary:hover {
    background-color: v-bind(hoverColor);
    color: #fff !important;
}
</style>
