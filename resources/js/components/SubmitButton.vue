<template>
   <button v-bind="$attrs" type="submit" class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx m-2" :disabled="isLoading">
        <template v-if="isLoading">
            <div style="display:flex;">
            <semipolar-spinner  :animation-duration="2000" :size="20" color="#fff" /> <span class="ml-3"><slot> Submit</slot></span>
            </div>
        </template>
        <template v-else>
            <slot>Submit</slot>
        </template>
    </button>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { AtomSpinner,SemipolarSpinner } from 'epic-spinners'

defineProps(['isLoading'])

</script>

<style>

</style>
