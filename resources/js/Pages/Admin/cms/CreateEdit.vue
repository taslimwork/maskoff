<template lang="">
<Head title="Edit CMS"/>
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">
        <form @submit.prevent="submit">
            <div class="form-group validated row">
                <!-- {{ $form }} -->
                <div class="form-group col-lg-12">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <textarea id="title" v-model="form.title" class="form-control border-gray-200" placeholder="Title"></textarea>
                    <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
               </div>
               <div class="form-group col-lg-12">
               <img v-if="props.cms.page_banner_image" :src="'/'+props.cms.page_banner_image" alt="" style="width:100px;height:80px;margin-bottom: 5px;">
                    <label for="bannerImage">Banner Image</label>
                    <FilePond v-model="form.bannerImage" />
                    <span class="text-danger" v-if="form.errors.bannerImage">{{ form.errors.bannerImage }}</span>
                </div>
               <div class="form-group col-lg-12">
                    <label for="content">Content <span class="text-danger">*</span></label>
                    <CKeditor v-model="form.content" />
                    <span class="text-danger" v-if="form.errors.content">{{ form.errors.content }}</span>
                </div>
             </div>

             <div class="form-group col-lg-12" v-if="props.cms.slug=='contact-us'">
                <label for="contentImage">Content Image</label>
                <FilePond v-model="form.contentImage" :myFile="page.props.frontend_url +'/' +props.cms?.content_image1"/>
                <span class="text-danger" v-if="form.errors.contentImage">{{ form.errors.contentImage }}</span>
            </div>


            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                    <Link href="/admin/cms" class="btn btn-secondary">Cancel</Link>
                </div>
            </div>
        </form>
    </div>
</div>

</template>
<script setup>
import { onMounted } from 'vue'
import { useForm,router,usePage } from '@inertiajs/vue3'
import FileUpload from '@/components/FileUpload.vue'
import Datepicker from '@/components/Datepicker.vue'
import SubmitButton from '@/components/SubmitButton.vue'
import CKeditor from '@/components/Ckeditor.vue'
import FilePond from '../../../components/FilePond.vue';


const page = usePage()

const props = defineProps({
   errors:Object,
   cms:Object
})

const form = useForm({
  title: props.cms?.title || null,
  content: props.cms?.text_content || null,
  bannerImage: null,
  contentImage: null,
})

onMounted(()=>{
   emit.emit('pageName', 'CMS Management',[{title: "CMS", routeName:"admin.cms.index"},{title: "Edit", routeName:""}]);
})

function submit() {

  if(props.cms){
    form.post("/admin/cms/"+props.cms.id);
  }else{
    form.post('/admin/cms');
  }
}


</script>
