<template>
     <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="cursor: pointer;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                            <slot></slot>
                            <i v-if="sorting" class="fa fa-fw fa-sort pull-right" @click="ListHelper.sortBy(sortField)"></i>
                    </th>
</template>

<script setup>
    import ListHelper from '@/helpers/ListHelper';
const {sorting, sortField} = defineProps({sorting:{
    type:Boolean,
    default:true
}, sortField: {
    type: String,
    default: null
}})
console.log(sortField);
</script>


