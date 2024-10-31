<template lang="">
    <div>
        <Head title="Create User" v-if="!props.user"/>
        <Head title="Edit User" v-if="props.user"/>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit" >
                    <div class="form-group validated row">
                        <text-input v-model="form.first_name" :error="form.errors.first_name" label="First name" :required=true placeholder="First name"/>

                        <text-input v-model="form.last_name" :error="form.errors.last_name" label="Last name" :required=true placeholder="Last name"/>

                        <text-input type="text" v-model="form.username" :error="form.errors.username" label="Username" :required=true placeholder="Username"/>

                        <text-input type="email" v-model="form.email" :error="form.errors.email" label="Email" :required=true placeholder="Email"/>

                        <text-input type="number" v-model="form.phone" :error="form.errors.phone" label="Phone" :required=true placeholder="Phone"/>
                        
                        <div class="form-group col-lg-6">
                            <label for="dob">DOB<span class="text-danger">*</span></label>
                            <datepicker v-model="form.dob" placeholder="DOB" reverse-years :max-date="new Date()"/>
                            <span class="text-danger" v-if="form.errors.dob">{{ form.errors.dob }}</span>
                        </div>
                        
                        <text-input v-if="!props.user" type="password" v-model="form.password" :error="form.errors.password" label="Password" :required=true placeholder="Password"/>

                    <div class="form-group col-lg-6">
                        <label for="profile_photo">Profile Photo</label>
                        <FilePond v-model="form.profile_photo" :myFile="props.user?.profile_photo"/>
                            <span class="text-danger" v-if="form.errors.profile_photo">{{ form.errors.profile_photo }}</span>
                        </div>

                    <div class="form-group col-lg-6">
                            <label for="status" class="underline">Status</label>
                            <select id="status" class="form-control border-gray-200" v-model="form.status">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.status">{{ form.errors.status }}</span>
                    </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                            <!-- <button type="reset" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx">Reset</button> -->
                            <Link href="/admin/users" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Datepicker from '../../../components/Datepicker.vue'
import SubmitButton from '../../../components/SubmitButton.vue'
import TextInput from '../../../components/admin/TextInput.vue'
import FilePond from '../../../components/FilePond.vue'
import moment from 'moment'



const form = useForm({
    first_name: props.user?.first_name || null,
    last_name: props.user?.last_name || null,
    username: props.user?.username || null,
    email: props.user?.email || null,
    phone:props.user?.phone || null,
    dob: props.user?.dob ? new Date(props.user?.dob) : null,
    password: null,
    status: props.user?.active || 1,
    profile_photo: null,
})

const props = defineProps({
    errors: Object,
    user: Object
})

console.log(props.user);

const imageUrl = ref('');

onMounted(() => {
    // imageUrl.value = props.user?.profile_photo || '';
    if (props.user) {
        emit.emit('pageName', 'User Management', [{ title: "User List", routeName: "admin.users" }, { title: "Edit User", routeName: "" }]);

    } else {
        emit.emit('pageName', 'User Management', [{ title: "User List", routeName: "admin.users" }, { title: "Add User", routeName: "" }])
    }
})

function submit() {
    if (props.user) {
        form.post(route('admin.editUser', props.user.id));
    } else {
        form.post(route('admin.createUser'));
    }
}
</script>

<style type="text/css">
/* .dp__btn {
  display: none;
} */
</style>
