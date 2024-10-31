<template lang="">
<div>
  <Head title="Event List"/>
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
                       <Link :href="route('admin.createEvent')" class="btn btn-button kt-btn btn-sm kt-btn--icon button-fx">+ Add New</Link>
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
                                <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                aria-label="Company Email: activate to sort column ascending">Organizer<i
                                class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('organizer_name')"></i>
                                </th>
                                <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                aria-label="Company Email: activate to sort column ascending">Date<i
                                class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('event_date')"></i>
                                </th>
                                <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%;"
                                aria-label="Company Email: activate to sort column ascending">Time<i
                                class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('event_time')"></i>
                                </th>
                                <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                style="width: 15%;" aria-label="Status: activate to sort column ascending">Status</th>
                                <th class="align-center" rowspan="1" colspan="1" style="width: 10%;" aria-label="Actions">Actions</th>
                            </tr>

                            <tr class="filter">
                                <th>
                                    <input type="search" v-model="form.name" placeholder="" autocomplete="off"
                                        class="form-control-sm form-filter" />
                                </th>
                                <th>
                                    <input type="search" v-model="form.organizer_name" placeholder="" autocomplete="off"
                                        class="form-control-sm form-filter" />
                                </th>
                                <th>
                                    <!-- <input type="search" v-model="form.event_date" placeholder="" autocomplete="off"
                                        class="form-control-sm form-filter" /> -->
                                        <!-- <template class="form-control-sm form-filter">
                                        </template> -->
                                        <Datepicker v-model="form.event_date"/>
                                </th>
                                <th>
                                    <!-- <input type="search" v-model="form.event_time" placeholder="" autocomplete="off"
                                        class="form-control-sm form-filter" /> -->
                                        <!-- <Datepicker v-model="form.event_time" time-picker enable-seconds :auto-apply="false" disable-time-range-validation/> -->
                                </th>
                                <th>
                                    <select class="form-control form-control-sm form-filter kt-input" v-model="form.active"
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
                            <template v-for="event,index in events.data" :key="event.id">
                                <tr role="row" class="odd">
                                    <td>{{ event.name }}</td>
                                    <td>{{ event.organizer_name }}</td>
                                    <td>{{ event.event_date }}</td>
                                    <td>{{ event.event_time }}</td>
                                    <td class="align-center">
                                        <!-- <Link href="change-user-status" method="post" :data="{'id':user.id}"> -->
                                        <span @click="changeStatus(event.id)" style="cursor: pointer;" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                        :class="(event.active == 1) ? 'kt-badge--success':'kt-badge--warning'"
                                            >{{(event.active == 1) ? 'Active':'Inactive'}}</span>
                                            <!-- </Link> -->
                                    </td>


                                    <td nowrap="" class="align-center"> <span class="dropdown"><a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" >
                                        <i class="la la-ellipsis-h"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <Link class="dropdown-item" :href="route('admin.editEvent',event.id)" :class="{ 'disable-link' : new Date(event.event_date) < new Date()}"><i class="la la-edit"></i> Edit</Link>
                                            <button href="#" class="dropdown-item" @click="deleteRecode(event.id)"><i class="fa fa-trash"></i> Delete</button>
                                        </div>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <tr role="row" v-if="Object.keys(events.data).length == 0" class="odd text-center">
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
                    Showing {{events.from}} to {{events.to}} of {{events.total}} entries
                </div>
            </div>
            <div class="col-sm-12 col-md-7">
                  <div class="float-right">
                    <Bootstrap4Pagination
                            :data="events"
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

const {events,filters} = defineProps({ events: Object,filters:Object });


const form = reactive({
    name: filters.name || null,
    organizer_name: filters.organizer_name || null,
    event_date: filters.event_date || null,
    event_time: filters.event_time || '',
    active: filters.active || '',
})
// console.log('hhh');

const filterData = ref('');

onMounted(() => {

     emit.emit('pageName', 'Event Management',[{title: "Event List", routeName:"admin.events"}]);

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
        router.visit(route('admin.events'), {
        method: 'get',
        data: pickBy(form),
        preserveState: true
    });
    filterData.value = JSON.stringify(pickBy(form));
    // console.log(filterData.value)
}, 100));



const deleteRecode = (id) => {
 sw.confirm('deleteConfirm',id);
}

const deleteConfirm = (id) => {
    router.delete(route('admin.deleteEvent',id), {
        preserveScroll: true,
    });
}

const changeStatus = (id) => {
    sw.confirm('changeStatusConfirm',id,'Are you sure?',"You want to change the status!",'Yes, Change it!');
}

const changeStatusConfirm = (id) => {
    router.post(route('admin.changeEventStatus',id), {
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
<style type="text/css">

/* .dp__btn {
  display: none;
} */
</style>
