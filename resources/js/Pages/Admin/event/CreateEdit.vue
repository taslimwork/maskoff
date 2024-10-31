<template lang="">
    <div>
        <Head title="Create Event" v-if="!props.event"/>
        <Head title="Edit Event" v-if="props.event"/>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit" >
                    <div class="form-group validated row">
                        <text-input v-model="form.name" :error="form.errors.name" label="Name" :required=true placeholder="Event Name"/>
                        <div class="form-group col-lg-6">
                                <label for="event_type_id" class="underline">Event Types<span class="text-danger">*</span></label>
                                <select id="event_type_id" class="form-control border-gray-200" v-model="form.event_type_id">
                                    <option value="">Select Event Types</option>
                                    <option v-for="event_type,index in props.event_types" :value="event_type.id">{{event_type.name}}</option>
                                </select>
                                <span class="text-danger" v-if="form.errors.event_type_id">{{ form.errors.event_type_id }}</span>
                        </div>
                        <text-input v-model="form.organizer_name" :error="form.errors.organizer_name" label="Organizer Name" :required=false placeholder="Organizer Name"/>

                        <div class="form-group col-lg-6">
                            <label for="dob">Event Date<span class="text-danger">*</span></label>
                            <datepicker v-model="form.event_date" placeholder="Event date" reverse-years :min-date="moment(new Date).add(1, 'days').toDate()"/>
                            <span class="text-danger" v-if="form.errors.event_date">{{ form.errors.event_date }}</span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="time">Event Time<span class="text-danger">*</span></label>
                            <input type="time" class="form-control border-gray-200" v-model="form.event_time">
                            <span class="text-danger" v-if="form.errors.event_time">{{ form.errors.event_time }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                                <label for="event_location">Event Location<span class="text-danger">*</span></label>
                                <textarea id="event_location" v-model="form.event_location" class="form-control border-gray-200" placeholder="Event Location"></textarea>
                                <span class="text-danger" v-if="form.errors.event_location">{{ form.errors.event_location }}</span>
                        </div>
                        <!-- <div class="form-group col-lg-12">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea id="description" v-model="form.description" class="form-control border-gray-200" placeholder="Description"></textarea>
                                <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
                        </div> -->

                        <div class="form-group col-lg-12">
                            <label for="content">Description <span class="text-danger">*</span></label>
                            <CKeditor v-model="form.description" />
                            <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="content">Event Features</label>
                            <CKeditor v-model="form.event_features" />
                            <span class="text-danger" v-if="form.errors.event_features">{{ form.errors.event_features }}</span>
                        </div>
                        <!-- <div class="form-group col-lg-12">
                                <label for="event_features">Event features <span class="text-danger">*</span></label>
                                <textarea id="event_features" v-model="form.event_features" class="form-control border-gray-200" placeholder="Event features"></textarea>
                                <span class="text-danger" v-if="form.errors.event_features">{{ form.errors.event_features }}</span>
                        </div> -->
                        <div class="form-group col-lg-6">
                            <label for="image_file">Image</label>
                            <FilePond v-model="form.image_file" :myFile="props.event?.image_file"/>
                                <span class="text-danger" v-if="form.errors.image_file">{{ form.errors.image_file }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="image_file">Sponsors <a href="javascript:void(0);" @click="addSponsors()" class="btn-label-brand btn btn-sm btn-bold">+ Add Sponsor</a></label>
                            <div class="row" v-for="sponsor, sponsorKey, in form.sponsor_information" :key="sponsorKey">
                                <div class="form-group col-lg-5">
                                    <input type="text" class="form-control border-gray-200" v-model="sponsor.sponsor_name">
                                    <span class="text-danger">{{form.errors['sponsor_information.'+sponsorKey+'.sponsor_name']}}</span>
                                </div>
                                <div class="form-group col-lg-1">
                                    <a href="javascript:void(0);" @click="removeSponsors(sponsorKey)" class="btn-label-brand btn btn-sm btn-bold" v-if="sponsorKey>0">- Remove</a>
                                </div>
                            </div>
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
                            <Link href="/admin/events" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import {debounce,throttle,pickBy} from "lodash";
import Datepicker from '../../../components/Datepicker.vue'
import SubmitButton from '../../../components/SubmitButton.vue'
import TextInput from '../../../components/admin/TextInput.vue'
import FilePond from '../../../components/FilePond.vue'
import CKeditor from '@/components/Ckeditor.vue'
import moment from 'moment'

const props = defineProps({
    errors: Object,
    event: Object,
    event_types:Object,
    sponsor_information:Array
})

console.log(props.event);
const form = useForm({
    name: props.event?.name || null,
    event_type_id: props.event?.event_type_id || '',
    organizer_name: props.event?.organizer_name || null,
    event_date: props.event?.event_date ? new Date(props.event?.event_date) : null,
    event_time: props.event?.event_time || null,
    event_location: props.event?.event_location || null,
    description:props.event?.description || null,
    event_features: props.event?.event_features || null,
    sponsor_information: props.sponsor_information || [],
    status: props.event?.active.toString() || 1,
    image_file: null,
})

console.log('props.sponsor_informations');
console.log(props.sponsor_information);

const imageUrl = ref('');

onMounted(() => {
    // imageUrl.value = props.user?.profile_photo || '';
    if (props.user) {
        emit.emit('pageName', 'Event Management', [{ title: "Event List", routeName: "admin.events" }, { title: "Edit Event", routeName: "" }]);

    } else {
        emit.emit('pageName', 'Event Management', [{ title: "Event List", routeName: "admin.events" }, { title: "Add Event", routeName: "" }])
    }
})

function submit() {
    console.log('props.event');
    console.log(props.event);
    if (props.event) {
        form.post(route('admin.editEvent', props.event.id));
    } else {
        form.post(route('admin.createEvent'));
    }
}
const addSponsors = () => {
    form.sponsor_information.push({'sponsor_name':''});
}

const removeSponsors = (index) => {
     form.sponsor_information.splice(index,1);
}

// watch(form, debounce(() => {
//         console.log(props.sponsor_information);
// }, 100));
</script>

<style type="text/css">
/* .dp__btn {
  display: none;
} */
</style>
