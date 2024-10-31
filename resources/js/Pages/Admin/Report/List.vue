<template lang="">
    <div>
      <Head title="Report List"/>
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
                           <!-- <Link :href="route('admin.createUser')" class="btn btn-button kt-btn btn-sm kt-btn--icon button-fx">+ Add New</Link> -->
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
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">Reporter 
                                        <i class="fa fa-fw  pull-right" style="cursor: pointer;" ></i>
                                    </th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Company Agent: activate to sort column ascending">Report Type 
                                        <i class="fa fa-fw " style="cursor: pointer;" ></i>
                                    </th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Company Agent: activate to sort column ascending">Post 
                                        <i class="fa fa-fw  pull-right" style="cursor: pointer;"></i>
                                    </th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Company Email: activate to sort column ascending">Post Created By 
                                        <i class="fa fa-fw pull-right" style="cursor: pointer;"></i>
                                    </th>
    
                                    <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 15%;" aria-label="Status: activate to sort column ascending">Status
                                    </th>

                                    <!-- <th class="align-center" rowspan="1" colspan="1" style="width: 10%;" aria-label="Actions">Actions</th> -->
                                </tr>
    
                                <tr class="filter">
                                    <th>
                                        <input type="search" v-model="form.name" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                    </th>

                                    <th>
                                        <input type="search" v-model="form.report_type" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                    </th>

                                    <th>
                                        <input type="search" v-model="form.post" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                    </th>

                                    <th>
                                        <input type="search" v-model="form.creator" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                    </th>

                                    <th>
                                        <select class="form-control form-control-sm form-filter kt-input" v-model="form.status"
                                            title="Select" data-col-index="2">
                                            <option value="">Select One</option>
                                            <option value="1">Action Attempt</option>
                                            <option value="0">No Action Attempt</option>
                                        </select>
                                    </th>
                                    <!-- <th>
                                    </th> -->
                                </tr>
                            </thead>
                            <tbody v-auto-animate >
                                <!-- <tbody ref="scrollComponent" v-auto-animate > -->
                                <template v-for="report,index in reports.data" :key="report.id">
                                    <tr role="row" class="odd">
                                        <td class="sorting_1" tabindex="0">
                                            <div class="kt-user-card-v2">
                                                <div class="kt-user-card-v2__pic">
                                                        <img v-if="report.reporter_profile_photo != null" :src="report.reporter_profile_photo" style="width:150px; height:130px;">
                                                        <span v-if="report.reporter_profile_photo == null">{{ report.reporter_full_name.substr(0,1) }}</span>
                                                    <!-- <div class="kt-badge kt-badge--xl" :class="ListHelper.getRandomVal()">
    
                                                    </div> -->
                                                </div>
                                                <div class="kt-user-card-v2__details">
                                                    <span class="kt-user-card-v2__name">{{report.reporter_full_name}}</span>
                                                    <span class="kt-user-card-v2__name">Username: {{report.reporter_username}}</span>
                                                    <span class="kt-user-card-v2__email kt-link">Report on {{ListHelper.dateFormat(report.reporte_on)}} </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ report.type }}</td>
                                        <td v-html="report.post_description"></td>
                                        <td>
                                            <div class="kt-user-card-v2">
                                                <div class="kt-user-card-v2__pic">
                                                    <img v-if="report.post_creator_profile_photo != null" :src="report.post_creator_profile_photo" style="width:150px; height:130px;">
                                                    <span v-if="report.post_creator_profile_photo == null">{{ report.reporter_full_name.substr(0,1) }}</span>
                                                </div>
                                                <div class="kt-user-card-v2__details">
                                                    <span class="kt-user-card-v2__name">{{report.post_creator_full_name}}</span>
                                                    <span class="kt-user-card-v2__name">Username: {{report.post_creator_username}}</span>
                                                    <span class="kt-user-card-v2__email kt-link">Post on {{ListHelper.dateFormat(report.post_create_on)}} </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-center">
                                            <span @click="changeStatus(report.id)" style="cursor: pointer;" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                            :class="(report.status == 1) ? 'kt-badge--success':'kt-badge--warning'"
                                                >{{(report.status == 1) ? 'Action Attempt':'No Action Attempt'}}</span>
                                        </td>
    
                                        <!-- <td nowrap="" class="align-center"> <span class="dropdown"><a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" >
                                            <i class="la la-ellipsis-h"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <Link class="dropdown-item" :href="route('admin.groupMembers',report.id)"><i class="la la-users"></i> Members</Link>
                                                <button href="#" class="dropdown-item" @click="deleteRecode(report.id)"><i class="fa fa-trash"></i> Delete</button>
                                            </div>
                                            </span>
                                        </td> -->
                                    </tr>
                                </template>
                                <tr role="row" v-if="Object.keys(reports.data).length == 0" class="odd text-center">
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
                        Showing {{reports.from}} to {{reports.to}} of {{reports.total}} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                      <div class="float-right">
                        <Bootstrap4Pagination
                                :data="reports"
                                :limit=2
                                @pagination-change-page="ListHelper.setPageNum"
                            />
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    
    
    </template>
    
    
    <script setup>
    import { useForm,router,usePage } from '@inertiajs/vue3'
    import {ref,watch,reactive,onMounted, onUnmounted, watchEffect} from 'vue';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import {debounce,throttle,pickBy} from "lodash";
    import ListHelper from '@/helpers/ListHelper';
    import perPageDropdown from '@/components/admin/PerpageDropdown.vue';
    import Datepicker from '@/components/Datepicker.vue'
    
    const {reports,filters} = defineProps({ reports: Object,filters:Object });
    
    console.log(reports);
    const form = reactive({
        name: filters.name || null,
        report_type: filters.report_type || null,
        post: filters.moto || null,
        creator: filters.creator || null,
        status: filters.status || '',
    })
    
    const filterData = ref('');
    
    onMounted(() => {
    
         emit.emit('pageName', 'Report Management',[{title: "Report List", routeName:"admin.reports"}]);
    
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
    
    watch(form, debounce(() => {
            router.visit(route('admin.reports'), {
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
        router.delete(route('admin.userDelete',id), {
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