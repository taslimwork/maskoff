<template>
  <div :class="$attrs.class" class="form-group col-lg-6">
    <label v-if="label" class="form-label" :for="id">{{ label }}: <span class="text-danger" v-if="required">*</span> </label>
    <input :id="id" ref="input"  v-bind="{ ...$attrs, class: null }" class="form-control border-gray-200" :class="{ error: error }" :type="type" :value="modelValue" @input="updateModelValue" />
     <span class="text-danger" v-if="error">{{ error }}</span>
  </div>
</template>


<script setup>
import { v4 as uuid } from 'uuid'
import { ref } from 'vue'

const { id, type, error, label, modelValue, required } = defineProps(['id', 'type', 'error', 'label', 'modelValue', 'required'])
const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null)

const focus = () => {
  inputRef.value.focus()
}

const select = () => {
  inputRef.value.select()
}

const setSelectionRange = (start, end) => {
  inputRef.value.setSelectionRange(start, end)
}

const updateModelValue = (event) => {
  emit('update:modelValue', event.target.value)
}
</script>
