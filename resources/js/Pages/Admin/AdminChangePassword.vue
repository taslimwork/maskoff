<template>

<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">
    <h2>Change password</h2>
        <form @submit.prevent="submit">
            <div class="form-group validated row">
                <!-- {{ $form }} -->

                <div class="form-group col-lg-6">
                    <label for="old_password">Current Password <span class="text-danger">*</span></label>
                    <div class="password_box">
                        <input :type="showCurrentPassword ? 'text' : 'password'" id="old_password" v-model="form.old_password" class="form-control border-gray-200" placeholder="Current Password">
                        <div class="control">
                            <span class="icon is-small is-right">
                                <i @click="showCurrentPassword = !showCurrentPassword" class="fas" :class="{ 'fa-eye-slash': !showCurrentPassword, 'fa-eye': showCurrentPassword }"></i>
                            </span>
                        </div>
                    </div>
                    <span class="text-danger" v-if="form.errors.old_password">{{ form.errors.old_password }}</span>
               </div>

              <div class="form-group col-lg-6">
                    <label for="new_password">New password <span class="text-danger">*</span></label>
                    <div class="password_box">
                        <input :type="showNewPassword ? 'text' : 'password'" id="new_password" v-model="form.new_password" class="form-control border-gray-200" placeholder="New Password" ref="newPassword">
                        <div class="control">
                            <span class="icon is-small is-right">
                                <i @click="showNewPassword = !showNewPassword" class="fas" :class="{ 'fa-eye-slash': !showNewPassword, 'fa-eye': showNewPassword }"></i>
                            </span>
                        </div>
                    </div>
                    <span class="text-danger" v-if="form.errors.new_password">{{ form.errors.new_password }}</span>
               </div>

               <div class="form-group col-lg-6">
                    <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                    <div class="password_box">
                        <input :type="showConfirmPassword ? 'text' : 'password'" id="confirm_password" v-model="form.confirm_password" class="form-control border-gray-200" placeholder="Confirm Password">
                        <div class="control">
                            <span class="icon is-small is-right">
                                <i @click="showConfirmPassword = !showConfirmPassword" class="fas" :class="{ 'fa-eye-slash': !showConfirmPassword, 'fa-eye': showConfirmPassword }"></i>
                            </span>
                        </div>
                    </div>
                    <span class="text-danger" v-if="form.errors.confirm_password">{{ form.errors.confirm_password }}</span>
               </div>
             </div>
             <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                    <Link href="/admin/dashboard" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
                </div>
            </div>
        </form>
    </div>
</div>

</template>

<script setup>
import { ref, watch, reactive, onMounted } from 'vue';
import { useForm,router } from '@inertiajs/vue3'
import SubmitButton from '../../components/SubmitButton.vue'


const form = useForm({
    old_password: null,
    new_password: null,
    confirm_password: null,
})

const props = defineProps({
   errors:Object
})

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);


const newPassword = ref();
function submit() {

    form.post(route('admin.changePassword'),{
        preserveScroll: true,
        // onError: (errors) => {
        //     newPassword.value.focus();
        //     console.log(errors);
        // },
     });
}

</script>

<style>

</style>
