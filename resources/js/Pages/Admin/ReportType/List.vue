<template lang="">
    <div>
      <Head title="Report Type List"/>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <perPageDropdown />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="kt_table_1_filter" class="dataTables_filter">
                            <!-- <a :href="`/admin/user-export?filter=${filterData}`" type="button" class="btn btn-button kt-btn btn-sm kt-btn--icon button-fx">
                                <i class="la la-download"></i> Export
                            </a> &nbsp; -->
                            <button type="button" class="btn btn-button kt-btn btn-sm kt-btn--icon button-fx" @click="addReportType()"> + Add New</button>
                           <!-- <Link :href="route('admin.createEventType')" class="btn btn-button kt-btn btn-sm kt-btn--icon button-fx">+ Add New</Link> -->
                        </div>
                    </div>
                </div>
                <div class="row table-responsive">
                    <div class="col-sm-12">
                        <table
                            class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px;">
                            <thead>
                                <tr role="row">
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">Name <i
                                            class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('name')"></i>
                                    </th>
                                    <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 15%;" aria-label="Status: activate to sort column ascending">Status</th>
                                    <th class="align-center" rowspan="1" colspan="1" style="width: 10%;" aria-label="Actions">Actions</th>
                                </tr>
    
                                <tr class="filter">
                                    <th>
                                        <input type="search" v-model="form.name" placeholder="" autocomplete="off"
                                            class="form-control form-filter" />
                                    </th>
                                    <th>
                                        <select class="form-control form-control-sm form-filter kt-input" v-model="form.status"
                                            title="Select" data-col-index="2">
                                            <option value="">Select One</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-auto-animate >
                                <!-- <tbody ref="scrollComponent" v-auto-animate > -->
                                <template v-for="type,index in reportTypes.data" :key="type.id">
                                    <tr role="row" class="odd">
                                        <td class="sorting_1" tabindex="0">
                                            <div class="kt-user-card-v2">
                                                <div class="kt-user-card-v2__details">
                                                    <span class="kt-user-card-v2__name">{{type.name}}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-center">
                                            <span @click="changeStatus(type.id)" style="cursor: pointer;" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                            :class="(type.status == 1) ? 'kt-badge--success':'kt-badge--warning'"
                                                >{{(type.status == 1) ? 'Active':'Inactive'}}</span>
                                        </td>
    
                                        <td nowrap="" class="align-center"> <span class="dropdown"><a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" >
                                            <i class="la la-ellipsis-h"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <!-- <Link class="dropdown-item" @click="deleteRecode(type.id)"><i class="la la-edit"></i> Edit</Link> -->
                                                <button type="button" class="dropdown-item" @click="addReportType(type)"><i class="la la-edit"></i> Edit</button>
                                                <button type="button" class="dropdown-item" @click="deleteRecode(type.id)"><i class="fa fa-trash"></i> Delete</button>
                                            </div>
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr role="row" v-if="Object.keys(reportTypes.data).length == 0" class="odd text-center">
                                        <td colspan="5" >No data Found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                        Showing {{reportTypes.from}} to {{reportTypes.to}} of {{reportTypes.total}} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                      <div class="float-right">
                        <Bootstrap4Pagination
                                :data="reportTypes"
                                :limit=2
                                @pagination-change-page="ListHelper.setPageNum"
                            />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Type Add modal Modal -->
    <transition name="fade">
    <div v-if="showHideTypeModal" class="modal-backdrop">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form @submit.prevent="submitReportType">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Report Type</h5>
                        <button type="button" @click="showHideTypeModal=false" class="btn btn-sm btn-circle">
                            <i class="flaticon-close"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-lg-12">
                            <label for="time">Report Type<span class=text-danger>*</span></label>
                            <input type="text" class="form-control border-gray-200" v-model="addForm.name">
                            <span class="text-danger" v-if="addForm.errors.name">{{ addForm.errors.name }}</span>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="time">Report Type<span class=text-danger>*</span></label>
                            <select v-model="addForm.status" class="form-control border-gray-200">
                                <option value="">Select One</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger" v-if="addForm.errors.status">{{ addForm.errors.status }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="showHideTypeModal=false">Close</button>
                        <submit-button :disabled="addForm.processing"
                            :isLoading="addForm.processing">Submit</submit-button>
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
    import { router, useForm } from '@inertiajs/vue3'
    import { ref, watch, reactive, onMounted, onUnmounted } from 'vue';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import {debounce, pickBy} from "lodash";
    import ListHelper from '@/helpers/ListHelper';
    import perPageDropdown from '@/components/admin/PerpageDropdown.vue';
    import SubmitButton from '@/components/SubmitButton.vue'

    
    const {reportTypes, filters} = defineProps({ reportTypes: Object, filters:Object });
    
    const showHideTypeModal = ref(false)
    const reportId = ref('');

    const form = reactive({
        name: filters.name || null,
        status: filters.status || '',
    })

    const addForm = useForm({
        name: null,
        status: 1,
    })
    
    const filterData = ref('');
    
    onMounted(() => {
        emit.emit('pageName', 'Report Type Management',[{title: "Report Type List", routeName:"admin.reportTypes"}]);
        
        emit.on('deleteConfirm', function (arg1) {
            deleteConfirm(arg1);
        });
    
        emit.on('changeStatusConfirm', function (arg1) {
            changeStatusConfirm(arg1);
        });

        filterData.value = JSON.stringify(pickBy(form));
    });
    
    onUnmounted(() => {
        emit.off("changeStatusConfirm");
        emit.off("deleteConfirm");
    });

    const addReportType = (reportType) => {
        if (reportType) {
            console.log(reportType);
            reportId.value = reportType.id
            addForm.name = reportType.name
            addForm.status = reportType.status
        }else{
            reportId.value = null
            addForm.name = null
            addForm.status = 1
        }
        addForm.errors = [];
        showHideTypeModal.value = true;
        document.body.classList.add('modal-open');
    }

    const submitReportType = () => {
        if (reportId.value) {
            addForm.post(route('admin.editReportType', {reportType: reportId.value}),{
                onSuccess: () => {
                    showHideTypeModal.value = false;
                }
            })
        }else{
            addForm.post(route('admin.createReportType'),{
                onSuccess: () => {
                    showHideTypeModal.value = false;
                }
            })
        }
    }
    
    watch(form, debounce(() => {
            router.visit(route('admin.reportTypes'), {
            method: 'get',
            data: pickBy(form),
            preserveState: true
        });
        filterData.value = JSON.stringify(pickBy(form));
    }, 100));
    
    const deleteRecode = (id) => {
     sw.confirm('deleteConfirm',id);
    }
    
    const deleteConfirm = (id) => {
        router.delete(route('admin.reportTypeDelete',id), {
            preserveScroll: true,
        });
    }
    
    const changeStatus = (id) => {
        sw.confirm('changeStatusConfirm',id,'Are you sure?',"You want to change the status!",'Yes, Change it!');
    }
    
    const changeStatusConfirm = (id) => {
        router.post(route('admin.reportChangeStatus',id), {
            preserveScroll: true,
        })
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
    