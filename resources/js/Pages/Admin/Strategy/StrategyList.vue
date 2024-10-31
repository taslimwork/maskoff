<template lang="">
    <Head title="Strategy List"/>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <Table :ListData="strategies">
                 <template #perpage>
                 </template>
                <template #TableButton>
                </template>
                <template #TableHead>
                    <TableTh :sorting=false  style="width:20%">Name</TableTh>
                    <TableTh :sorting=false style="width:35%">Description</TableTh>
                    <TableTh :sorting=false style="width:35%">Types</TableTh>
                    <TableTh :sorting=false>Actions</TableTh>
                </template>
                <template #TableBody>
                    <tr role="row" class="odd" v-for="strategy in strategies.data" :key="strategy.id" >
                        <td class="sorting_1" tabindex="0">
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">
                                        <img v-if="strategy.image_path != null" :src="strategy.image_path" style="width:150px; height:130px;">
                                        <span >{{strategy.name}}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ strategy.description?.substring(0,210)+".." }}</td>
                        <td>
                            <!-- <span class="badge badge-info" v-for="typeList in strategy.types" :key="'type'+typeList.id">{{typeList.strategy_type}}</span> -->
                            <div class="btn-group mr-2 mt-2" role="group" v-for="typeList in strategy.types" :key="'type'+typeList.id">
                                <button id="btnGroupVerticalDrop2" type="button" class="btn btn-label-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{typeList.strategy_type}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                    <a class="dropdown-item" href="javascript:void(0)" @click="deleteType(typeList.id)">Delete</a>
                                </div>
                            </div>
                            <button type="button" @click="clickToAddStrategy(strategy.id)" class="btn btn-success btn-sm btn-icon btn-circle" data-toggle="modal" data-target="#kt_modal_6"><i class="fa fa-plus"></i></button>
                        </td>
                        <td nowrap="" class="align-center">
                            <span class="dropdown">
                            <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <Link class="dropdown-item" :href="route('admin.strategy-sub',strategy.id)"><i class="la la-list"></i> Sub Strategies</Link>
                                <button class="dropdown-item" @click="deleteRecode(strategy.id)"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                            </span>
                        </td>
                    </tr>
                    <tr role="row" v-if="Object.keys(strategies.data).length == 0" class="odd text-center">
                        <td colspan="5" >No data Found</td>
                    </tr>
                </template>
            </Table>
        </div>

    <!--Type Add modal Modal -->
    <transition name="fade">
        <div v-if="showHideTypeModal" class="modal-backdrop">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form @submit.prevent="submitStrategyType">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add Type</h5>
                            <button type="button" @click="closeModal()" class="btn btn-sm btn-circle">
                                <i class="flaticon-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group col-lg-12">
                                <label for="time">Strategy Type<span class=text-danger>*</span></label>
                                <input type="text" class="form-control border-gray-200" v-model="typeForm.strategy_type">
                                <span class="text-danger" v-if="typeForm.errors.strategy_type">{{
                                    typeForm.errors.strategy_type }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Close</button>
                            <submit-button :disabled="typeForm.processing"
                                :isLoading="typeForm.processing">Submit</submit-button>
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


const { strategies, filters, errors } = defineProps({ strategies: Object, filters: Object, errors: Object });

const showHideTypeModal = ref(false);
// const form = reactive({
//     name: filters.name || null,
//     email: filters.email || null,
//     phone: filters.phone || null,
//     message: filters.message || null,
// })

const typeForm = useForm({
    strategy_type: null,
    strategy_id: null
})

const clickToAddStrategy = (id) => {
    typeForm.errors = [];
    showHideTypeModal.value = true;
    // document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    typeForm.strategy_type = null;
    typeForm.strategy_id = id;
}

const closeModal = () => {
    showHideTypeModal.value = false;
    document.body.style.overflow = 'auto';
}

function submitStrategyType() {
    typeForm.post(route('admin.Strategy-type.add'), {
        preserveState: true,
        onSuccess: () => {
            showHideTypeModal.value = false;  // Close the modal on successful submit
        }
    });
}


onMounted(() => {

    emit.emit('pageName', 'Strategy  Management', [{ title: "Strategy", routeName: "" }]);

    emit.on('deleteConfirm', function (arg1) {
        deleteConfirm(arg1);
    });
    emit.on('deleteTypeConfirm', function (arg1) {
        deleteTypeConfirm(arg1);
    });
});


onUnmounted(() => {
    emit.off("deleteConfirm");
    emit.off("deleteTypeConfirm");
});


const deleteRecode = (id) => {
    sw.confirm('deleteConfirm', id);
}

const deleteConfirm = (id) => {
    router.post(route('admin.strategy.destroy', [id]));
    selectedId.value = [];
}

const deleteType = (id) => {
    sw.confirm('deleteTypeConfirm', id);
}

const deleteTypeConfirm = (id) => {
    router.post(route('admin.strategy-type.destroy', [id]));
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
