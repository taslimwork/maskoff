<template lang="">
    <div class="col-md-4 offset-md-4" style="margin-top:90px;">
      <div  class="d-flex justify-content-center">
                <img :src="$page.props.logo" alt="" style="margin-bottom:10px;">
      </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Reset Password</h4>
                <form @submit.prevent="submit">
                    <div class="form-group">
                    <label for="password">Password<span class="text-danger">*</span></label>
                    <input type="password" id="password" class="form-control" placeholder="Password" v-model="form.password" >
                    <span class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</span>
                    </div>
                    <div class="form-group">
                    <label for="confirmPassword">Confirm Password<span class="text-danger">*</span></label>
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password" v-model="form.confirm_password">
                    <span class="text-danger" v-if="form.errors.confirm_password">{{ form.errors.confirm_password }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary"  :disabled="form.processing">
                        <div :class="{'spinner-border':form.processing, 'spinner-border-sm':form.processing}" role="status"></div>
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>

</template>

<script setup>
import { onMounted, reactive, ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const mainColor = computed(() => usePage().props.theme.mainColor)
const hoverColor = computed(() => usePage().props.theme.hoverColor)
const buttonColor = computed(() => usePage().props.theme.buttonColor)

const form = useForm({
  password: '',
  confirm_password: '',
})

onMounted(()=>{
  // if(usePage().props.flash.success){
  //   toaster.success(usePage().props.flash.success);
  // }

  // if(usePage().props.flash.error){
  //   toaster.error(usePage().props.flash.error);
  // }

})

const props = defineProps({
  errors:Object,
  email:String
})

function submit() {
  form.post(route('frontend.resetPassword'))
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
    background-color: v-bind(hoverColor);
    color: #fff !important;
}
</style>
