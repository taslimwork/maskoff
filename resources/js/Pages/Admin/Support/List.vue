<template lang="">
    <div>
      <Head title="Support List"/>
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
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">Name <i
                                    class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('username')"></i>
                                    </th>
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                    aria-label="Company Email: activate to sort column ascending">Email <i
                                    class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('email')"></i></th>
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                    aria-label="Company Agent: activate to sort column ascending">Query Subject <i
                                    class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('subject')"></i></th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                    aria-label="Company Agent: activate to sort column ascending">Description <i
                                    class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('description')"></i></th>
    
                                    <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                    style="width: 15%;" aria-label="Status: activate to sort column ascending">Status</th>
                                    <th class="align-center" rowspan="1" colspan="1" style="width: 10%;" aria-label="Actions">Actions</th>
                                </tr>
    
                                <tr class="filter">
                                    <th>
                                        <input type="search" v-model="form.username" placeholder="" autocomplete="off"
                                            class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <input type="search" v-model="form.email" placeholder="" autocomplete="off"
                                            class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <input type="search" v-model="form.subject" placeholder="" autocomplete="off"
                                            class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <input type="search" v-model="form.description" placeholder="" autocomplete="off"
                                            class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <select class="form-control form-control-sm form-filter kt-input" v-model="form.status"
                                            title="Select" data-col-index="2">
                                            <option value="">Select One</option>
                                            <option value="1">Completed</option>
                                            <option value="0">Pending</option>
                                        </select>
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-auto-animate >
                                <!-- <tbody ref="scrollComponent" v-auto-animate > -->
                                <template v-for="support,index in supports.data" :key="support.id">
                                    <tr role="row" class="odd">
                                        <td class="sorting_1" tabindex="0">
                                            <div class="kt-user-card-v2">
                                                <div class="kt-user-card-v2__pic">
                                                        <img v-if="support.profile_photo != null" :src="support.profile_photo" style="width:150px; height:130px;">
                                                        <span v-if="support.profile_photo == null">{{ support.full_name.substr(0,1) }}</span>
                                                    <!-- <div class="kt-badge kt-badge--xl" :class="ListHelper.getRandomVal()">
    
                                                    </div> -->
                                                </div>
                                                <div class="kt-user-card-v2__details">
                                                    <span class="kt-user-card-v2__name">{{support.full_name}}</span>
                                                    <span class="kt-user-card-v2__email "><b>Username: {{ support.username }}</b> </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a class="kt-link" :href="'mailto:' + support.email">{{support.email}}</a></td>
                                        <td>{{ support.subject.length > 200 ? support.subject.substring(0,200)+"..." : support.subject }}</td>
                                        <td>{{ support.description.length > 200 ? support.description.substring(0,200)+"..." : support.description }}</td>
                                        <td class="align-center">
                                            <!-- <Link href="change-user-status" method="post" :data="{'id':user.id}"> -->
                                            <span @click="changeStatus(support.id)" style="cursor: pointer;" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                            :class="(support.status == 1) ? 'kt-badge--success':'kt-badge--warning'"
                                                >{{(support.status == 1) ? 'Completed':'Pending'}}</span>
                                                <!-- </Link> -->
                                        </td>
    
                                        <td nowrap="" class="align-center"> <span class="dropdown"><a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" >
                                            <i class="la la-ellipsis-h"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <!-- <Link class="dropdown-item" :href="route('admin.editUser',support.id)"><i class="la la-eye"></i> View</Link> -->
                                                <button href="#" class="dropdown-item" @click="viewSupportTickit(support)"><i class="fa fa-eye"></i> View</button>
                                            </div>
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr role="row" v-if="Object.keys(supports.data).length == 0" class="odd text-center">
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
                        Showing {{supports.from}} to {{supports.to}} of {{supports.total}} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                      <div class="float-right">
                        <Bootstrap4Pagination
                                :data="supports"
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
                    <form @submit.prevent="submitStrategyType">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Support</h5>
                            <button type="button" @click="showHideTypeModal=false" class="btn btn-sm btn-circle">
                                <i class="flaticon-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row pb-5 d-flex align-items-center">
                                <div class="kt-user-card-v2__pic col-lg-5">
                                        <img v-if="supportDet.profile_photo != null" :src="supportDet.profile_photo" style="width:100px; height:100px;">
                                        <span v-if="supportDet.profile_photo == null">{{ supportDet.full_name.substr(0,1) }}</span>
                                </div>
                                <div class="kt-user-card-v2__details col-lg-7">
                                    <h3 class="kt-user-card-v2__name">{{supportDet.full_name}}</h3>
                                    <span class="kt-user-card-v2__email "><b>Username: {{ supportDet.username }}</b> </span>
                                    <p class="kt-user-card-v2__email "><b>Email: <a class="kt-link" :href="'mailto:' + supportDet.email">{{supportDet.email}}</a></b> </p>
                                    
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label style="font-size:15px">Query Subject:</label>
                                <div><h5>{{ supportDet.subject }}</h5></div>
                                <label style="font-size:15px">Description:</label>
                                <textarea cols="59" rows="7" readOnly disabled>{{ supportDet.description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showHideTypeModal=false">Close</button>
                            <!-- <submit-button :disabled="typeForm.processing"
                                :isLoading="typeForm.processing">Submit</submit-button> -->
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
    import { useForm,router,usePage } from '@inertiajs/vue3'
    import {ref,watch,reactive,onMounted, onUnmounted, watchEffect} from 'vue';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import {debounce,throttle,pickBy} from "lodash";
    import ListHelper from '@/helpers/ListHelper';
    import perPageDropdown from '@/components/admin/PerpageDropdown.vue';
    import Datepicker from '@/components/Datepicker.vue'
    
    const {supports,filters} = defineProps({ supports: Object,filters:Object });
    
    const showHideTypeModal = ref(false);
    const supportDet = ref({});
    
    const form = reactive({
        username: filters.username || null,
        email: filters.email || null,
        subject: filters.subject || null,
        description: filters.description || null,
        status: filters.status || '',
    })
    
    const filterData = ref('');
    
    onMounted(() => {
    
         emit.emit('pageName', 'Feedback Management',[{title: "Support List", routeName:"admin.supports"}]);
    
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
            router.visit(route('admin.supports'), {
            method: 'get',
            data: pickBy(form),
            preserveState: true
        });
        filterData.value = JSON.stringify(pickBy(form));
    }, 100));
    
    const viewSupportTickit = (support) => {
        showHideTypeModal.value = true;
        document.body.classList.add('modal-open');
        supportDet.value = support
    }
    
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
        router.post(route('admin.changeSupportStatus',id), {
            preserveScroll: true,
        })
    }
    const exportUserData = ()=>{
        router.post(route('admin.userExport'), {
            preserveScroll: true,
            data: form,
        });
    
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
    