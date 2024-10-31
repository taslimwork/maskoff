<template lang="">
    <div>
      <Head title="Group List"/>
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
                                id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1615px!important; overflow-x:auto">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">Posted By 
                                            <i class="fa fa-fw  pull-right" style="cursor: pointer;" ></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 7%;"
                                            aria-label="Company Agent: activate to sort column ascending">Post Type 
                                            <i class="fa fa-fw fa-sort" style="cursor: pointer;" @click="ListHelper.sortBy('post_type')"></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 17%;"
                                            aria-label="Company Agent: activate to sort column ascending">Description 
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('post_description')"></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 17%;"
                                            aria-label="Company Agent: activate to sort column ascending">Content 
                                            <i class="fa fa-fw  pull-right" style="cursor: pointer;" ></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 10%;"
                                            aria-label="Company Email: activate to sort column ascending">Posted on
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('created_at')"></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 8%;"
                                            aria-label="Company Agent: activate to sort column ascending">Total Comments 
                                            <i class="fa fa-fw  pull-right" style="cursor: pointer;" ></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 8%;"
                                            aria-label="Company Agent: activate to sort column ascending">Total Reactions 
                                            <i class="fa fa-fw  pull-right" style="cursor: pointer;" ></i>
                                        </th>
        
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%;" aria-label="Status: activate to sort column ascending">Block/Unblock
                                        </th>

                                        <th class="align-center" rowspan="1" colspan="1" style="width: 10%;" aria-label="Actions">Actions</th>
                                    </tr>
        
                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.name" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>

                                        <th>
                                            <input type="search" v-model="form.type" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>

                                        <th>
                                            <input type="search" v-model="form.description" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>

                                        <th>
                                            <!-- <input type="search" v-model="form.creator" placeholder="" autocomplete="off" class="form-control-sm form-filter" /> -->
                                        </th>

                                        <th>
                                            <Datepicker v-model="form.created_at"/>
                                        </th>

                                        <th>
                                            <!-- <input type="search" v-model="form.total_comment" placeholder="" autocomplete="off" class="form-control-sm form-filter" /> -->
                                        </th>

                                        <th>
                                            <!-- <input type="search" v-model="form.total_reaction" placeholder="" autocomplete="off" class="form-control-sm form-filter" /> -->
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
                                    </tr>
                                </thead>
                                <tbody v-auto-animate >
                                    <!-- <tbody ref="scrollComponent" v-auto-animate > -->
                                    <template v-for="post,index in postList.data" :key="post.id">
                                        <tr role="row" class="odd">
                                            <td class="sorting_1" tabindex="0">
                                                <div class="kt-user-card-v2">
                                                    <div class="kt-user-card-v2__pic">
                                                            <img v-if="post.user.image != null" :src="post.user.image" style="width:150px; height:130px;">
                                                            <span v-if="post.user.image == null">{{ post.user.full_name.substr(0,1) }}</span>
                                                        <!-- <div class="kt-badge kt-badge--xl" :class="ListHelper.getRandomVal()">
        
                                                        </div> -->
                                                    </div>
                                                    <div class="kt-user-card-v2__details">
                                                        <span class="kt-user-card-v2__name">{{post.user.full_name}}</span>
                                                        <span class="kt-user-card-v2__email"><b>Username:</b> {{post.user.username}}</span>
                                                        <!-- <span class="kt-user-card-v2__email kt-link">Posted on {{ListHelper.dateFormat(post.created_at)}} </span> -->
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ post.post_type }}</td>
                                            <td>{{ post.post_description.length > 200 ? post.post_description?.substring(0,210)+".." : post.post_description }}</td>
                                            <td>
                                                <!-- 'text','photo','video','audio','poll','article' -->
                                                <template v-if="post.post_details.length > 0">
                                                    <div v-for="postDetail in post.post_details" :key="postDetail.id">
                                                        <div v-if="(post.post_type == 'photo')">
                                                            <img :src="baseUrl + postDetail.post_contents">
                                                        </div>
                                                        <div v-else-if="(post.post_type == 'video')">
                                                            <video width="150" height="75" controls>
                                                                <source :src="baseUrl + postDetail.post_contents" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                        <div v-else-if="(post.post_type == 'audio')">
                                                            <audio controls style="width:235px;">
                                                                <source :src="baseUrl + postDetail.post_contents" type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </div>
                                                        <div v-else>
                                                            <p>{{postDetail.post_contents.length > 200 ? postDetail.post_contents?.substring(0,50)+".." : postDetail.post_contents }}</p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </td>
                                            <td>{{ moment(post.created_at).format("DD-MM-YYYY") }}</td>
                                            <td>{{post.number_of_comments}}</td>
                                            <td>{{post.number_of_reaction}}</td>
                                            <td class="align-center">
                                                <!-- <Link href="change-user-status" method="post" :data="{'id':user.id}"> -->
                                                <span @click="changeStatus(post.id)" style="cursor: pointer;" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                                :class="(post.active == 1) ? 'kt-badge--success':'kt-badge--warning'"
                                                    >{{(post.active == 1) ? 'Unblock':'Block'}}</span>
                                                    <!-- </Link> -->
                                            </td>
        
                                            <td nowrap="" class="align-center"> <span class="dropdown"><a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" >
                                                <i class="la la-ellipsis-h"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button type="button" @click="viewPost(post.id)" class="dropdown-item">
                                                        <i class="fa fa-eye"></i>View Detail
                                                    </button>
                                                    <button type="button" @click="deleteRecode(post.id)" class="dropdown-item">
                                                        <i class="fa fa-trash"></i>Delete
                                                    </button>
                                                    <!-- <button href="#" class="dropdown-item" @click="deleteRecode(post.id)"><i class="fa fa-trash"></i> Delete</button> -->
                                                </div>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr role="row" v-if="Object.keys(postList.data).length == 0" class="odd text-center">
                                            <td colspan="9" >No data Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{postList.from}} to {{postList.to}} of {{postList.total}} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination
                                    :data="postList"
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
                <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Post Detail</h5>
                            <button type="button" @click="closeModal()" class="btn btn-sm btn-circle">
                                <i class="flaticon-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                                <div class="kt-portlet__body kt-portlet__body--fit">
                                    <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides"
                                        style="min-height: 150px; ">  
                                        <!-- background-image: url(https://ui-avatars.com/api/?name=Admin&color=7F9CF5&background=EBF4FF&size=228) -->
                                        <!-- currentPost.post_type -->
                                        <div v-for="postDetail in currentPost.post_details.data" :key="postDetail.id">
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
                                            <div v-else style="width:100%;" v-html="postDetail.post_contents">
                                            </div>
                                        </div>
                                        <div class="kt-widget19__shadow1"></div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget19__wrapper">
                                        <div class="kt-widget19__content">

                                            <div class="kt-widget19__userpic">
                                                <img :src="currentPost.user.image" alt="Image">
                                            </div>
                                            <div class="kt-widget19__info">
                                                <span class="kt-widget19__username">
                                                    {{currentPost.user.username}}
                                                </span>
                                                <span class="kt-widget19__name">
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
                                        
                                    </div>
                                </div>
                                <div class="kt-portlet__body kt-portlet__body--fit">
                                    <div class="mb-4">
                                        <h5>Description</h5>
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
                                        <div id="all_comments_scroll_in_modal_none">
                                            <template v-for="comment in currentPost?.list_of_comments.data" :key="comment.id">
                                                <div class="row">
                                                    <div class="kt-timeline-v3__item kt-timeline-v3__item--brand col-10">
                                                        <span class="kt-timeline-v3__item-time kt-font-info">{{ moment(comment.created_at).format('Do MMM YY LT')}}</span>
                                                        <div class="kt-timeline-v3__item-desc">
                                                            <span class="kt-timeline-v3__item-text">
                                                                {{ comment.post_description}}
                                                            </span><br>
                                                            <span class="kt-timeline-v3__item-user-name">
                                                                <span class="kt-link kt-link--dark kt-timeline-v3__item-link">
                                                                    {{ comment?.user?.user_id == isAdmin ? 'You' : comment?.user?.username}}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <i class="fa fa-trash text-end" @click="deleteComment(comment.id)" style="cursor:pointer;"></i>
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
    import { useForm,router,usePage } from '@inertiajs/vue3'
    import {ref,watch,reactive,onMounted, onUnmounted, watchEffect, computed} from 'vue';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import {debounce,throttle,pickBy} from "lodash";
    import ListHelper from '@/helpers/ListHelper';
    import perPageDropdown from '@/components/admin/PerpageDropdown.vue';
    import Datepicker from '@/components/Datepicker.vue'
    import moment from 'moment';
    import SubmitButton from '@/components/SubmitButton.vue';


    
    const props = defineProps({ postList: Object, filters: Object, errors: Object });
    // console.log(props.postList);
    const baseUrl = computed(() => usePage().props.frontend_url + "/storage/")
    const isAdmin = computed(() => usePage().props.isAdmin);

    const showHideTypeModal = ref(false);
    const currentPost = ref({});

    const form = useForm({
        name: props?.filters.name || null,
        type: props?.filters.type || null,
        description: props?.filters.description || null,
        created_at: props?.filters.created_at || null,
        total_comment: props?.filters.total_comment || null,
        total_reaction: props?.filters.total_reaction || null,
        active: props?.filters.active || "",
    })

    const commentFrom = useForm({
        post_comment: null,
        post_status_id: currentPost.value?.id,
    })
    
    const filterData = ref('');
    
    onMounted(() => {
        emit.emit('pageName', 'Post Management', [{ title: "Post", routeName: "admin.post-status" }]);

        emit.on('deleteConfirm', function (arg1) {
            deleteConfirm(arg1);
        });

        emit.on('deleteCommentConfirm', function (arg1) {
            deleteCommentConfirm(arg1);
        });

        emit.on('changeStatusConfirm', function (arg1) {
            changeStatusConfirm(arg1);
        });

        filterData.value = JSON.stringify(pickBy(form));
    });
    
    onUnmounted(() => {
        emit.off("changeStatusConfirm");
        emit.off("deleteConfirm");
        // emit.off("deleteCommentConfirm");
    });
    
    watch(form, debounce(() => {
            router.visit(route('admin.post-status'), {
            method: 'get',
            data: pickBy(form),
            preserveState: true
        });
        filterData.value = JSON.stringify(pickBy(form));
    }, 100));
    
    const submitComment = () => {
        // console.log(currentPost.value, "currentPost");
        commentFrom.post(route('admin.post-status.comment'), {
            preserveState: true,
            onSuccess: (response) => {
                // console.log("response", response);
                commentFrom.reset();
                currentPost.value = response.props.postList.data.find(comment => comment.id === currentPost.value.id)
                commentFrom.post_status_id = currentPost.value.id;

                // console.log(response.props.postList);
                // console.log(currentPost.value, "After comment");

                // showHideTypeModal.value = false;  // Close the modal on successful submit
            }
        });
    }

    const viewPost = (postId) => {
        // console.log(postId, "postId");
        // console.log(props.postList, "postlist");
        currentPost.value = {};
        currentPost.value = props.postList.data.find(post => post.id === postId);
        // console.log("On first time view", currentPost.value);
        commentFrom.post_status_id = currentPost.value.id;
        showHideTypeModal.value = true;
        document.body.style.overflow = 'hidden';
        // setTimeout(function () {
        //     $("#all_comments_scroll_in_modal").niceScroll({ cursorwidth: '5px', autohidemode: false, zindex: 999 });
        //     var scrollableDiv = $('#all_comments_scroll_in_modal');
        //     var lastElement = scrollableDiv.children().last();
        //     scrollableDiv.scrollTop(lastElement.offset().top);
        // }, 200);
    }

    const closeModal = () => {
        showHideTypeModal.value=false; 
        document.body.style.overflow = 'auto';
    }
    
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
        // selectedId.value = [];
    }

    const deleteComment = (id) => {
        // console.log("delete id ", id);
        sw.confirm('deleteCommentConfirm', id);
    }

    const deleteCommentConfirm = (id) => {
        router.delete(route('admin.post-status.comment.delete', [id]),{
            onSuccess: (response) =>{
                currentPost.value = response.props.postList.data.find(comment => comment.id === currentPost.value.id)
            }
        });
    }

</script>
<style scoped>
    .form-control-sm {
        width: 100%;
    }
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
</style>
