<template lang="">
    <div class="col-md-4 offset-md-4" style="margin-top:90px;">
      <div  class="d-flex justify-content-center" >
        <img :src="$page.props.logo" alt="logo" style="margin-bottom:20px;">
      </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin Login</h4>
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" v-model="form.email" class="form-control" placeholder="Email" >
                        <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <div class="password_box">
                            <input :type="showNewPassword ? 'text' : 'password'" id="password" v-model="form.password" class="form-control border-gray-200" placeholder="Password">
                            <div class="control">
                                <span class="icon is-small is-right">
                                    <i @click="showNewPassword = !showNewPassword" class="fas" :class="{ 'fa-eye ': showNewPassword, 'fa-eye-slash': !showNewPassword }"></i>
                                </span>
                            </div>
                        </div>
                        <span class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary"  :disabled="form.processing">
                        <div :class="{'spinner-border':form.processing, 'spinner-border-sm':form.processing}" role="status">
                        </div>Login
                    </button>
                    <div>
                        <Link :href="route('frontend.forgotPassword')" class="float-right">Forgot Password</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const showNewPassword = ref(false);

const form = useForm({
  email: null,
  password: null,
})

const props = defineProps({
  errors:Object
})

/* onMounted(()=>{
  if(usePage().props.flash.success){
    toaster.success(usePage().props.flash.success);
  }

  if(usePage().props.flash.error){
    toaster.error(usePage().props.flash.error);
  }

}) */

function submit() {
  form.post(route('admin.login'))
}

const mainColor = computed(() => usePage().props.theme.mainColor)
const hoverColor = computed(() => usePage().props.theme.hoverColor)
const buttonColor = computed(() => usePage().props.theme.buttonColor)

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
        color: #fff !important;
        background-color: v-bind(mainColor) !important;
        border-color: #007bff !important;
    }
    .btn .btn-primary :hover {
        color: #fff !important;
        background-color: v-bind(hoverColor) !important;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: .875rem !important;
        line-height: 1.5 !important;
        border-radius: 0.2rem !important;
    }
    /* update start RN 21-04-2023 */
    .password_box{
        position: relative;
    }
    .password_box .control{
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        max-width: 15px;
    }
    .password_box input[type="password"], .password_box input[type="text"]{
        padding-right: 30px;
    }
    .table_fixed_width{
        min-height: 400px;
    }
    .no_data{
        margin: 50px 0 20px;
    }
</style>
