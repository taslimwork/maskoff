<template lang="">
    <div>
        <Head title="Create Group Type" v-if="!props.groupType"/>
        <Head title="Edit Group Type" v-if="props.groupType"/>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit" >
                    <div class="form-group validated row">
                        <text-input type="text" v-model="form.type" :error="form.errors.type" label="Group Type" :required=true placeholder="Group Type"/>

                        <div class="form-group col-lg-6">
                                <label for="status" class="underline">Status<span class="text-danger">*</span> </label>
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
                            <Link :href="route('admin.groupTypes')" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
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
    groupType: Object
})

const form = useForm({
    type: props.groupType?.type || null,
    status: props.groupType?.status.toString() || 1,
})


onMounted(() => {
    if (props.groupType) {
        emit.emit('pageName', 'Master Data Management', [{ title: "Group Type List", routeName: "admin.groupTypes" }, { title: "Edit Group Type", routeName: "" }]);

    } else {
        emit.emit('pageName', 'Master Data Management', [{ title: "Group Type List", routeName: "admin.groupTypes" }, { title: "Add Group Type", routeName: "" }])
    }
})

function submit() {
    if (props.groupType) {
        form.post(route('admin.editGroupType', props.groupType.id));
    } else {
        form.post(route('admin.createGroupType'));
    }
}
</script>

