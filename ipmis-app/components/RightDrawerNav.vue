<template>
    <div>
        <!-- Backdrop -->
        <div v-if="isOpen" @click="closeDrawer" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity">
        </div>

        <!-- Drawer -->
        <div :class="[
            'fixed top-0 right-0 h-full w-1/6 overflow-y-auto bg-white shadow-lg z-50 transform transition-transform',
            isOpen ? 'translate-x-0' : 'translate-x-full'
        ]" @click.stop>

            <div class="drawer-header p-4 flex justify-between items-center border-b">
                <slot name="header">Default Header</slot>
                <button @click="closeDrawer" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>
            <!-- <div class="p-4 flex justify-between items-center border-b">
                <button @click="closeDrawer" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div> -->
            <div class="p-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});
const emit = defineEmits(['update:isOpen', 'drawerClosed']);

const closeDrawer = () => {
    emit('update:isOpen', false);
    emit('drawerClosed', true)
};

onMounted( () => {
    console.log("right nav mounted")
})
</script>