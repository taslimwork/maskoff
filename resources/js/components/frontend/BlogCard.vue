<template>
    <div class="news-slider-card">
        <figure>
            <Link :href="`/blog/${blog.slug}`">
                <img v-if="blog.blog_photo" :src="blog.blog_photo" alt="image">
            </Link>
        </figure>

        <span class="blue-txt">{{ formattedDate(blog.created_at) }}</span>

        <h3 class="hFive"> <Link :href="`/blog/${blog.slug}`">{{blog.title.substring(0,30)+".."}}</link></h3>

        <div v-html="blog.content.substring(0,100)+'..'"></div>
        <Link v-if="pageName == 'blogList'" class="cmn_btn" :href="`/blog/${blog.slug}`"  style="margin-top:15px;">Read more</link>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3'
    import moment from 'moment';

    const props = defineProps({ blog:Object, pageName:String});

    const blog = props.blog;
    const pageName = props.pageName;

    function formattedDate(date)
    {
        return moment(date).format('DD MMM, YYYY');
    }
</script>


