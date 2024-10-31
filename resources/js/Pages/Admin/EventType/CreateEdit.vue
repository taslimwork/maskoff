<template lang="">
    <div>
        <Head title="Create Event Type" v-if="!props.eventType"/>
        <Head title="Edit Event Type" v-if="props.eventType"/>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit" >
                    <div class="form-group validated row">
                        <text-input type="text" v-model="form.name" :error="form.errors.name" label="Event Type" :required=true placeholder="Event Type"/>

                        <div class="form-group col-lg-6">
                                <label for="status" class="underline">Status<span class="text-danger">*</span> </label>
                                <select id="status" class="form-control border-gray-200" v-model="form.active">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span class="text-danger" v-if="form.errors.active">{{ form.errors.active }}</span>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                            <!-- <button type="reset" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx">Reset</button> -->
                            <Link :href="route('admin.eventTypes')" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import SubmitButton from '../../../components/SubmitButton.vue'
import TextInput from '../../../components/admin/TextInput.vue'

const props = defineProps({
    errors: Object,
    eventType: Object
})

const form = useForm({
    name: props.eventType?.name || null,
    active: props.eventType?.active.toString() || 1,
})


onMounted(() => {
    if (props.eventType) {
        emit.emit('pageName', 'Event Management', [{ title: "Event Type List", routeName: "admin.eventTypes" }, { title: "Edit Event Type", routeName: "" }]);

    } else {
        emit.emit('pageName', 'Event Management', [{ title: "Event Type List", routeName: "admin.eventTypes" }, { title: "Add Event Type", routeName: "" }])
    }
})

function submit() {
    if (props.eventType) {
        form.post(route('admin.editEventType', props.eventType.id));
    } else {
        form.post(route('admin.createEventType'));
    }
}
</script>

