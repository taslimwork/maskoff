<template lang="">
    <Head title="Sub Strategy List"/>
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">
    <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon kt-hide">
            <i class="la la-gear"></i>
        </span>
        <h3 class="kt-portlet__head-title text-center">
            {{strategy.name}}
        </h3>
    </div>

    <Table :ListData="subStrategies">
            <template #perpage>
            </template>
        <template #TableButton>
            <button type="button" @click="clickToAddStrategy(null)" class="btn btn-button kt-btn btn-sm kt-btn--icon button-fx"><i class="fa fa-plus"></i>Add New</button>
        </template>

        <template #TableHead>
            <TableTh :sorting=false  style="width:20%">Title</TableTh>
            <TableTh :sorting=false style="width:35%">Description</TableTh>
            <TableTh :sorting=false style="width:35%">Type</TableTh>
            <TableTh :sorting=false>Actions</TableTh>
        </template>
        <template #TableBody>
            <tr role="row" class="odd" v-for="subStrategy in subStrategies.data" :key="subStrategy.id" >
                <td class="sorting_1" tabindex="0">
                    <div class="kt-user-card-v2">
                        <div class="kt-user-card-v2__pic">
                                <img v-if="subStrategy.image_path != null" :src="subStrategy.image_path" style="width:150px; height:130px;">
                                <span style="margin-left:10px;">{{subStrategy.title}}</span>
                        </div>
                    </div>
                </td>
                <td>{{ subStrategy.description?.replace(/<[^>]*>?/gm, '').substring(0,210)+".." }}</td>
                <td>
                    <span class="badge badge-info" >{{subStrategy?.type?.strategy_type}}</span>

                </td>
                <td nowrap="" class="align-center">
                    <span class="dropdown">
                    <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                    <i class="la la-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" @click="clickToAddStrategy(subStrategy)"><i class="fa fa-edit"></i> Edit</button>
                        <button class="dropdown-item" @click="deleteRecode(subStrategy.id)"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                    </span>
                </td>
            </tr>
            <tr role="row" v-if="Object.keys(subStrategies.data).length == 0" class="odd text-center">
                <td colspan="5" >No data Found</td>
            </tr>
        </template>
    </Table>
</div>

<!--Type Add modal Modal -->
<transition name="fade">
    <div v-if="showHideTypeModal" class="modal-backdrop">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form @submit.prevent="submitStrategyType">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Sub Strategy ({{strategy.name}})</h5>
                        <button type="button" @click="closeModal()" class="btn btn-sm btn-circle">
                            <i class="flaticon-close"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- <div class="form-group col-lg-6">
                                <label for="time">Strategy Name</label>
                                <input type="text" class="form-control border-gray-200" v-model="strategy.name"
                                    readonly>
                            </div> -->
                            <div class="form-group col-lg-6">
                                <label for="strategy_type_id" class="underline">Strategy Type <span class="text-danger">*</span></label>
                                <select id="strategy_type_id" class="form-control border-gray-200"
                                    v-model="form.strategy_type_id">
                                    <option value="">Select Status </option>
                                    <template v-for="types in strategy?.strategy_type" :key="types.id">
                                        <option :value="types.id">{{types.strategy_type}}</option>
                                    </template>
                                </select>
                                <span class="text-danger" v-if="form.errors.strategy_type_id">{{
                                    form.errors.strategy_type_id }}</span>
                            </div>


                            <div class="form-group col-lg-6">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-gray-200" v-model="form.title">
                                <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="image">Image</label>
                                <FilePond v-model="form.image" :myFile="strategy_sub_image_path" />
                                <span class="text-danger" v-if="form.errors.image">{{ form.errors.image }}</span>
                            </div>

                            <div class="form-group col-lg-12">
                                <label for="description">Description<span class=text-danger>*</span></label>
                                <CKeditor v-model="form.description" />
                                <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Close</button>
                        <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</transition>
<!-- end:: Type Add modal -->
</div>
</template>


<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, reactive, watch, onUnmounted } from 'vue';
import Datepicker from '@/components/Datepicker.vue'
import ListHelper from '@/helpers/ListHelper';
import { debounce, throttle, pickBy } from "lodash";
import Table from '@/components/admin/Table.vue';
import TableTh from '@/components/admin/TableTh.vue';
import SubmitButton from '../../../components/SubmitButton.vue'
import CKeditor from '@/components/Ckeditor.vue'
import FilePond from '../../../components/FilePond.vue';


const { subStrategies, filters, strategy, errors } = defineProps({ subStrategies: Object, filters: Object, strategy: Object, errors: Object });

console.log(strategy.strategy_type);
const showHideTypeModal = ref(false);

const form = useForm({
    strategy_type_id: '',
    strategy_id: null,
    title: null,
    description: null,
    image: null,
})

const strategy_sub_data_id = ref(null);
const strategy_sub_image_path = ref(null);

const clickToAddStrategy = (editSubStrategy) => {
    if (editSubStrategy == null) {
        strategy_sub_data_id.value = null;
        strategy_sub_image_path.value = null;
        showHideTypeModal.value = true;
        document.body.style.overflow = 'hidden';
        form.strategy_type_id = '';
        form.strategy_id = strategy.id;
        form.title = null;
        form.description = null;
        form.image = null;
    }
    else {
        strategy_sub_data_id.value = editSubStrategy.id;
        showHideTypeModal.value = true;
        document.body.style.overflow = 'hidden';
        form.strategy_type_id = editSubStrategy?.strategy_type_id || '';
        form.strategy_id = editSubStrategy?.strategy_id || strategy.id;
        form.title = editSubStrategy?.title || null;
        form.description = editSubStrategy?.title || null;
        form.image = null;
        strategy_sub_image_path.value = editSubStrategy?.image_path || null;;
    }

}

function submitStrategyType() {
    if(strategy_sub_data_id.value)
    {
        form.post(route('admin.strategy-sub.update',{id:strategy_sub_data_id.value}), {
            preserveState: true,
            onSuccess: () => {
                showHideTypeModal.value = false;  // Close the modal on successful submit
                document.body.style.overflow = 'auto';
            }
        });
    }
    else{
        form.post(route('admin.strategy-sub.add'), {
        preserveState: true,
        onSuccess: () => {
            showHideTypeModal.value = false;  // Close the modal on successful submit
            document.body.style.overflow = 'auto';
        }
    });
    }

}

const closeModal = () => {
    showHideTypeModal.value = false;
    document.body.style.overflow = 'auto';
    form.errors = []
}


onMounted(() => {
    emit.emit('pageName', 'Sub Strategy Management', [{ title: "Strategy", routeName: "admin.strategies" },{ title: "Sub Strategy", routeName: "" }]);
    emit.on('deleteConfirm', function (arg1) {
        deleteConfirm(arg1);
    });
});


onUnmounted(() => {
    emit.off("deleteConfirm");
});


const deleteRecode = (id) => {
    sw.confirm('deleteConfirm', id);
}

const deleteConfirm = (id) => {
    router.post(route('admin.strategy-sub.delete', [id]));
    selectedId.value = [];
}





</script>
<style scoped>
.ShowModal {
    display: block;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
