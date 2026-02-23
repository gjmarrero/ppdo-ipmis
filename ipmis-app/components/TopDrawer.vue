<template>
    <div>

        <div v-if="isOpen" @click="closeDrawer" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity">
        </div>

        <div v-bind="$attrs" :class="[
            'fixed top-0 left-0 right-0 overflow-y-auto bg-drawerbg shadow-lg z-50 transform transition-transform rounded-b-3xl',
            'h-auto max-h-[90vh] overflow-y-auto',
            isOpen ? 'translate-y-0' : '-translate-y-full'
        ]" @click.stop>
            <div class="drawer-header p-2 pl-4 flex justify-between items-center border-b">
                <slot name="header">Default Header</slot>
                <button @click="closeDrawer" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>
            <div class="p-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
defineOptions({
    inheritAttrs: false
})

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:isOpen', 'drawerClosed']);

const closeDrawer = () => {
    emit('update:isOpen', false);
    emit('drawerClosed', true);
};
</script>
