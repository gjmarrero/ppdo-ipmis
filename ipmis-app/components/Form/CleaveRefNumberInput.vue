<script setup>
import { onMounted, ref } from "vue"
import Cleave from "cleave.js"

const props = defineProps({
    modelValue: String,
    placeholder: String,
    name: String,
})

const emit = defineEmits(["update:modelValue"])
const inputRef = ref(null)

onMounted(() => {
    new Cleave(inputRef.value, {
        delimiters: ['-', '-', '-'],
        blocks: [4, 4, 3, 3],   // xxxx-xxxx-xxx-xxx
        uppercase: true,        // optional
        numericOnly: true       // optional (digits only)
    }).on('rawValueChanged', (event) => {
        emit("update:modelValue", event.target.value)
    })
})
</script>

<template>
    <input ref="inputRef" :value="modelValue" :placeholder="placeholder" :name="name"
        class="border p-2 rounded w-full" />
</template>
