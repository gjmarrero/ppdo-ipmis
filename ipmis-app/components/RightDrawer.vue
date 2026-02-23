<template>
  <div>
    <div v-if="isOpen" @click="closeDrawer" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"></div>

    <div v-bind="$attrs" :class="[
      'fixed top-0 right-0 overflow-y-auto bg-drawerbg border border-drawerborder shadow-lg z-50 transform transition-transform duration-300 w-[800px] min-w-[800px] max-w-[90vw] rounded-b-3xl',
      height === 'full' ? 'h-full' : 'h-auto max-h-[90vh]',
      isOpen ? 'translate-x-0' : 'translate-x-full'
    ]" @click.stop>
      <div class="drawer-header p-4 flex justify-between items-center border-b">
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
  height: {
    type: String,
    default: "full"
  }
});

const emit = defineEmits(['update:isOpen', 'drawerClosed']);

const closeDrawer = () => {
  emit('update:isOpen', false);
  emit('drawerClosed', true);
};
</script>
