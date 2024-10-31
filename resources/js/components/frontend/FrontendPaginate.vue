<template lang="">

<div class="blog-pagination">
        <ul class="pagination">
            <li v-for="link,key in props.data.links" class="blog-page-1" :class="{'current-page' : link.active}" aria-current="page" :key="link.label"
            >
            <template v-if="link.url">
                <Link :href="link.url">
                <span v-if="key == 0"><img src="frontend_assets/images/pagi-lft.svg" alt=""></span>
                <span v-else-if="key == props.data.last_page+1"> <img src="frontend_assets/images/pagi-rt.svg" alt=""></span>
                <span v-else v-html="link.label"></span>
                </Link>
            </template>

            <template v-else>
                <span class="page-link" ><img src="frontend_assets/images/pagi-rt.svg" alt=""></span>
            </template>
        </li>
    </ul>
</div>
</template>
<script setup>
const props = defineProps({ data: Object })
// console.log(props.data.links, props.data.links[0]);
</script>
