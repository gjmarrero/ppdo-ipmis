<template>
    <button v-bind="$attrs" :class="`layout ${variant} ${shape} ${size}`">
        <slot></slot>
    </button>
</template>

<script setup>
defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: prop => {
            const isValid = ['primary', 'secondary', 'bordered', 'danger', 'success', 'ghost', 'default'].includes(prop);
            if (!isValid) console.warn('Invalid variant:', prop);
            return isValid;
        }
    },
    shape: {
        type: String,
        default: 'default',
        validator: prop => ['default', 'circle'].includes(prop),
    },
    size: {
        type: String,
        default: 'lg',
        validator: prop => ['sm', 'md', 'lg'].includes(prop),
    }
})
</script>

<style scoped>
.layout {
    @apply flex justify-center rounded-md px-3 py-1.5 text-sm font-semibold leading-6 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2;
}

.default {
    @apply bg-white text-gray-900 border border-gray-300 hover:bg-gray-100 focus-visible:outline-gray-500;
}

.primary {
    @apply bg-gray-900 text-white hover:bg-gray-900 focus-visible:outline-gray-900;
}

.secondary {
    @apply bg-gray-600 text-white hover:bg-gray-500 focus-visible:outline-gray-600;
}

.bordered {
    @apply bg-white text-indigo-800 border border-indigo-500 hover:bg-gray-50 focus-visible:outline-indigo-600;
}

.danger {
    @apply bg-red-600 text-white hover:bg-red-500 focus-visible:outline-red-600;
}

.success {
    @apply bg-green-600 text-white hover:bg-green-500 focus-visible:outline-green-600;
}

.ghost {
    @apply bg-transparent text-indigo-600 hover:bg-indigo-50 focus-visible:outline-indigo-600;
}

.outline {
    @apply border border-gray-300 text-gray-800 bg-white hover:bg-gray-100 focus-visible:outline-gray-600;
}

.default {
    @apply px-3 py-1.5 rounded-md;
}

.circle {
    @apply w-10 h-10 rounded-full p-0;
}

.sm {
    @apply px-1 py-1 text-xs;
}
.md {
    @apply px-2 py-1 text-sm;
}
.lg {
    @apply px-4 py-2 text-base;
}

</style>