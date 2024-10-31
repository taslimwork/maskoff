<template lang="">
    <Head title="Post List"/>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <Table :ListData="postList">
                 <template #perpage>
                 </template>
                <template #TableButton>
                </template>
                <template #TableHead>
                    <TableTh :sorting=false  style="width:15%">Post User</TableTh>
                    <TableTh :sortField="'post_type'" :sorting=true  style="width:10%">Post Type</TableTh>
                    <TableTh :sortField="'post_description'" :sorting=true style="width:20%">Description</TableTh>
                    <TableTh :sorting=false style="width:20%">Content</TableTh>
                    <TableTh :sortField="'created_at'" :sorting=true style="min-width:10%">Created At</TableTh>
                    <TableTh :sorting=false style="width:10%">Total Comments</TableTh>
                    <TableTh :sorting=false style="width:10%">Total Reactions</TableTh>
                    <TableTh :sorting=false style="width:8%">Block/Unblock</TableTh>
                    <TableTh :sorting=false>Actions</TableTh>
                </template>
                <template #TableFilter>
                    <th>
                        <input type="search" v-model="form.name" placeholder="" autocomplete="off"
                            class="form-control-sm form-filter" />
                    </th>
                    <th>
                        <input type="search" v-model="form.type" placeholder="" autocomplete="off"
                            class="form-control-sm form-filter" />
                    </th>
                    <th>
                        <input type="search" v-model="form.description" placeholder="" autocomplete="off"
                            class="form-control-sm form-filter" />
                    </th>
                    <th>
                        
                    </th>
                    <th >
                        <Datepicker v-model="form.created_at" />
                        <!-- <input type="search" v-model="form.created_at" placeholder="" autocomplete="off"
                            class="form-control-sm form-filter" /> -->
                    </th>
                    <th>
                        <!-- <input type="search" v-model="form.total_comment" placeholder="" autocomplete="off"
                            class="form-control-sm form-filter" /> -->
                    </th>
                    <th>
                        <!-- <input type="search" v-model="form.total_reaction" placeholder="" autocomplete="off"
                            class="form-control-sm form-filter" /> -->
                    </th>
                    <th>
                        <select class="form-control form-control-sm form-filter kt-input" v-model="form.active"
                            title="Select" data-col-index="2">
                            <option value="">Select One</option>
                            <option value="1">Unblock</option>
                            <option value="0">Block</option>
                        </select>
                    </th>
                    <th>
                    </th>
                </template>
                <template #TableBody>
                    <tr role="row" class="odd" v-for="postStatus in postList.data" :key="postStatus.id" >
                        <td>
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">
                                    <img v-if="postStatus.user.image != null" :src="postStatus.user.image" style="width:150px; height:130px;">
                                    <span>{{ postStatus.user.username }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="sorting_1" tabindex="0">
                          {{ postStatus.post_type}}
                        </td>
                        <td>{{ postStatus.post_description?.substring(0,210)+".." }}</td>
                        <td>
                            <!-- 'text','photo','video','audio','poll','article' -->
                            <template v-if="postStatus.post_details.length > 0">
                                <div v-for="postDetail in postStatus.post_details" :key="postDetail.id">
                                    <div v-if="(postStatus.post_type == 'photo')">
                                        <img :src="baseUrl + postDetail.post_contents">
                                    </div>
                                    <div v-else-if="(postStatus.post_type == 'video')">
                                        <video width="150" height="75" controls>
                                            <source :src="baseUrl + postDetail.post_contents" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <div v-else-if="(postStatus.post_type == 'audio')">
                                        <audio controls style="width:235px;">
                                            <source :src="baseUrl + postDetail.post_contents" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                    <div v-else>
                                        <p>{{postDetail.post_contents?.substring(0,50)+".."}}</p>
                                    </div>
                                </div>
                            </template>
                        </td>
                        <td>
                            {{ moment(postStatus.created_at).format("DD-MM-YYYY") }}
                        </td>
                        <td>
                            <a href="javascript:void(0);"> {{postStatus.number_of_comments}}</a>
                        </td>
                        <td>
                            <a href="javascript:void(0);">{{postStatus.number_of_reaction}}</a>
                        </td>
                        <td class="align-center">
                            <!-- <Link href="change-user-status" method="post" :data="{'id':user.id}"> -->
                            <span @click="changeStatus(postStatus.id)" style="cursor: pointer;"
                                class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                :class="(postStatus.active == 1) ? 'kt-badge--success':'kt-badge--warning'">{{(postStatus.active == 1) ?
                                'Unblock':'Block'}}</span>
                            <!-- </Link> -->
                        </td>
                        <td nowrap="" class="align-center">
                            <span class="dropdown">
                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <!-- <Link class="dropdown-item" :href="route('admin.postStatus-sub',postStatus.id)"><i class="la la-list"></i> Sub Strategies</Link> -->
                                    <button type="button" @click="clickToAddStrategy(postStatus.id)" class="dropdown-item"><i
                                            class="fa fa-eye"></i>View Detail</button>
                                    <button class="dropdown-item" @click="deleteRecode(postStatus.id)"><i class="fa fa-trash"></i>
                                        Delete</button>
                                </div>
                            </span>
                        </td>
                    </tr>
                    <tr role="row" v-if="Object.keys(postList.data).length == 0" class="odd text-center">
                        <td colspan="5">No data Found</td>
                    </tr>
                </template>
            </Table>
        </div>

<!--Type Add modal Modal -->
<transition name="fade">
    <div v-if="showHideTypeModal" class="modal-backdrop">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Post Detail</h5>
                    <button type="button" @click="showHideTypeModal=false" class="btn btn-sm btn-circle">
                        <i class="flaticon-close"></i></button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                        <div class="kt-portlet__body kt-portlet__body--fit">
                            <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides"
                                style="min-height: 150px; ">  
                                <!-- background-image: url(https://ui-avatars.com/api/?name=Admin&color=7F9CF5&background=EBF4FF&size=228) -->
                                <!-- currentPost.post_type -->
                                <div v-for="postDetail in currentPost.post_details" :key="postDetail.id">
                                    <div v-if="(currentPost.post_type == 'photo')">
                                        <img :src="baseUrl + postDetail.post_contents">
                                    </div>
                                    <div v-else-if="(currentPost.post_type == 'video')">
                                        <video width="150" height="75" controls>
                                            <source :src="baseUrl + postDetail.post_contents" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <div v-else-if="(currentPost.post_type == 'audio')">
                                        <audio controls style="width:235px;">
                                            <source :src="baseUrl + postDetail.post_contents" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                    <div v-else style="width:100%; height:150px;overflow-y:auto;" v-html="postDetail.post_contents">
                                    </div>
                                </div>
                                <div class="kt-widget19__shadow"></div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget19__wrapper">
                                <div class="kt-widget19__content">

                                    <div class="kt-widget19__userpic">
                                        <img :src="currentPost.user.image" alt="Image">
                                    </div>
                                    <div class="kt-widget19__info">
                                        <a href="javascript:void(0);" class="kt-widget19__username">
                                            {{currentPost.user.username}}
                                        </a>
                                        <span class="kt-widget19__time">
                                            {{currentPost.user.full_name}}
                                        </span>
                                    </div>
                                    <div class="kt-widget19__stats mr-5">
                                        <span class="kt-widget19__number kt-font-brand">
                                            {{currentPost.number_of_reaction}}
                                        </span>
                                        <a href="javascript:void(0);" class="kt-widget19__comment">
                                            Likes
                                        </a>
                                    </div>
                                    <div class="kt-widget19__stats">
                                        <span class="kt-widget19__number kt-font-brand">
                                            {{currentPost.number_of_comments}}
                                        </span>
                                        <a href="javascript:void(0);" class="kt-widget19__comment">
                                            Comments
                                        </a>
                                    </div>
                                </div>
                                <div class="kt-widget19__text">
                                    {{currentPost.post_description }}
                                </div>
                            </div>
                        </div>
                        <!--  <div class="kt-timeline-v3 mb-5">
                                <h5>Reactions :</h5>
                                <div class="kt-timeline-v3__items">
                                    <div class="kt-list-pics kt-list-pics--sm kt-padding-l-20">
                                        <a href="#"><img src="/admin_assets/media/users/100_13.jpg" title=""></a>
                                        <a href="#"><img src="/admin_assets/media/users/100_11.jpg" title=""></a>
                                        <a href="#"><img src="/admin_assets/media/users/100_14.jpg" title=""></a>
                                    </div>
                                </div>
                            </div> -->
                        <div class="kt-timeline-v3">
                            <div class="kt-timeline-v3__items">
                                <h5>Comment List :</h5>
                                <div id="all_comments_scroll_in_modal">
                                    <template v-for="comment in currentPost?.list_of_comments" :key="comment.id">
                                            <div class="kt-timeline-v3__item kt-timeline-v3__item--brand">
                                                <span class="kt-timeline-v3__item-time kt-font-info">{{ moment(comment.created_at).format('Do MMM YY LT')}}</span>
                                                <div class="kt-timeline-v3__item-desc">
                                                    <span class="kt-timeline-v3__item-text">
                                                        {{ comment.post_description}}
                                                    </span><br>
                                                    <span class="kt-timeline-v3__item-user-name">
                                                        <span class="kt-link kt-link--dark kt-timeline-v3__item-link">
                                                            {{comment.user.username}}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="kt-timeline-v3 mb-5 mt-5">
                            <h5>Add Comment :</h5>
                            <div class="kt-timeline-v3__items">
                                <form @submit.prevent="submitComment">
                                    <div class="form-group col-lg-12">
                                        <div class="input-group">
                                            <textarea type="text" class="form-control"
                                                v-model="commentFrom.post_comment"
                                                placeholder="Write comment..."></textarea>
                                            <div class="input-group-append">
                                                <submit-button :disabled="commentFrom.processing"
                                                    :isLoading="commentFrom.processing">Submit</submit-button>
                                            </div>
                                            <span class="text-danger" v-if="commentFrom.errors.title">{{
                                                commentFrom.errors.title }}</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" @click="showHideTypeModal=false">Close</button>
                        <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button> -->
                </div>

            </div>
        </div>
    </div>
</transition>
<!-- end:: Type Add modal -->
</div>
</template>


<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, reactive, watch, onUnmounted, onUpdated, computed } from 'vue';
import Datepicker from '@/components/Datepicker.vue'
import ListHelper from '@/helpers/ListHelper';
import { debounce, throttle, pickBy } from "lodash";
import Table from '@/components/admin/Table.vue';
import TableTh from '@/components/admin/TableTh.vue';
import SubmitButton from '../../../components/SubmitButton.vue';
import moment from 'moment';
// import 'jquery.nicescroll';

const { postList, filters, errors } = defineProps({ postList: Object, filters: Object, errors: Object });
const baseUrl = computed(() => usePage().props.frontend_url + "/storage/")

const filterData = ref('');

onUpdated(() => {
});


onMounted(() => {

    emit.emit('pageName', 'Post Management', [{ title: "Post", routeName: "" }]);

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

const changeStatus = (id) => {
    sw.confirm('changeStatusConfirm', id, 'Are you sure?', "You want to change the status!", 'Yes, Change it!');
}

const changeStatusConfirm = (id) => {
    router.post(route('admin.post-status.StatusChange', id), {
        preserveScroll: true,
    })
}


const deleteRecode = (id) => {
    sw.confirm('deleteConfirm', id);
}

const deleteConfirm = (id) => {
    router.delete(route('admin.post-status.destroy', [id]));
    selectedId.value = [];
}


const form = useForm({
    name: filters.name || null,
    type: filters.type || null,
    description: filters.description || null,
    created_at: filters.created_at || null,
    total_comment: filters.total_comment || null,
    total_reaction: filters.total_reaction || null,
    active: filters.active || "",
})

watch(form, debounce(() => {
            router.visit(route('admin.post-status'), {
            method: 'get',
            data: pickBy(form),
            preserveState: true
        });
        filterData.value = JSON.stringify(pickBy(form));
    }, 100));

const showHideTypeModal = ref(false);
const currentPost = ref({});

const commentFrom = useForm({
    post_comment: null,
    post_status_id: currentPost.value?.id,
})

function submitComment() {
    console.log(currentPost.value, "currentPost");alert('dfkjg');
    commentFrom.post(route('admin.post-status.comment'), {
        preserveState: true,
        onSuccess: (data) => {
            console.log(data);
            commentFrom.reset();

            // showHideTypeModal.value = false;  // Close the modal on successful submit
        }
    });

}

const clickToAddStrategy = (editSubStrategy) => {
    currentPost.value = {};
    currentPost.value = postList.data.find(post => post.id === editSubStrategy);

    commentFrom.post_status_id = currentPost.value.id;
    showHideTypeModal.value = true;
    setTimeout(function () {
        $("#all_comments_scroll_in_modal").niceScroll({ cursorwidth: '5px', autohidemode: false, zindex: 999 });
        var scrollableDiv = $('#all_comments_scroll_in_modal');
        var lastElement = scrollableDiv.children().last();
        scrollableDiv.scrollTop(lastElement.offset().top);
    }, 200);
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

.kt-timeline-v3 .kt-timeline-v3__item .kt-timeline-v3__item-time {
    font-weight: 600;
    font-size: 9px;
}

#all_comments_scroll_in_modal {
    max-height: 160px;
    overflow: scroll;
}
.table_fixed_width #kt_table_1{
    width: 1929px!important;
    overflow-x: auto;
}
</style>
