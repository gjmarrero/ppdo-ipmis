<template>
    <label :for="labelFor" v-bind="$attrs" class="block text-sm/6 font-medium text-textsecondary" :class="class">
        <slot></slot>
    </label>
</template>

<script setup>
    defineProps({
        class: {
            type: String,
            default: '',
        },
        labelFor: {
            type: String,
            default: ''
        }
    })
</script>