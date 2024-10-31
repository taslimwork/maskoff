<template lang="">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <Table :ListData="contactUsDataList">
                 <template #perpage>
                    <Icon v-if="selectedId.length" @click="multipleDelete" icon="material-symbols:delete" color="red" width="30" height="30" style="cursor: pointer; margin-right:10px;"/>
                    <!-- <button class="btn-danger" v-if="selectedId.length" @click="multipleDelete"><Icon icon="material-symbols:delete" color="white" width="30" height="30" /></button> -->
                 </template>

                <template #TableButton>

                </template>

                <template #TableHead>
                    <TableTh @click="ListHelper.sortBy('name')"  style="width:20%">Name</TableTh>
                    <TableTh @click="ListHelper.sortBy('email')" style="width:20%">Email</TableTh>
                    <TableTh @click="ListHelper.sortBy('phone')" style="width:20%">Phone</TableTh>
                    <TableTh @click="ListHelper.sortBy('message')" style="width:30%">Message</TableTh>
                    <TableTh :sorting=false>Actions</TableTh>
                </template>


                <template #TableBody>
                    <tr role="row" class="odd" v-for="contactUsData in contactUsDataList.data" :key="contactUsData.id" >
                        <td class="sorting_1" tabindex="0">
                            <!-- <input type="checkbox" class="kt-checkbox" v-model="selectedId" :value="contactUsData.id" /> -->
                            {{contactUsData.name}}
                        </td>
                        <td><a :href="'mailto:' + contactUsData.email">{{ contactUsData.email }}</a></td>
                        <td>{{ contactUsData.phone }}</td>
                        <td>{{ contactUsData.message?.substring(0,210)+".." }}</td>
                        <td nowrap="" class="align-center">
                            <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button href="#" class="dropdown-item" @click="deleteRecode(contactUsData.id)"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                            </span>
                        </td>
                    </tr>
                    <tr role="row" v-if="Object.keys(contactUsDataList.data).length == 0" class="odd text-center">
                        <td colspan="5" >No data Found</td>
                    </tr>
                </template>
            </Table>
        </div>
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





    const { contactUsDataList, filters } = defineProps({ contactUsDataList: Object, filters: Object });

    const form = reactive({
        name: filters.name || null,
        email: filters.email || null,
        phone: filters.phone || null,
        message: filters.message || null,
    })

   /*  watch(form, debounce(() => {
        router.visit(route('admin.contactUsData.list'), {
            method: 'get',
            data: pickBy(form),
            preserveState: true
        });
    }, 100)); */


    onMounted(() => {

        emit.emit('pageName', 'Contact us Management', [{ title: "Contact us", routeName: "" }]);

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
        router.delete(route('admin.contactUsData.destroy',[id]));
        selectedId.value = [];
    }

    const selectedId = ref([]);
    const checkedAll = ref(false);

    watch(checkedAll, (newVal) => {
        selectedId.value = [];
        if (newVal) {
            contactUsDataList.data.map((item) => {
                selectedId.value.push(item.id);
            })
        }
    });

    const multipleDelete = () => {
        deleteRecode(selectedId.value);
    }


</script>
