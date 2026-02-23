<template>
  <div class="relative w-full mt-2" tabindex="-1" @focusout="handleFocusOut">
    <FormInput v-model="query" @focus="isOpen = true" @keydown.arrow-down.prevent="moveSelection(1)"
      @keydown.arrow-up.prevent="moveSelection(-1)"
      @keydown.enter.prevent="selectOption(filteredOptions[highlightedIndex])" @keydown.esc="closeDropdown"
      class="w-full border border-gray-300 rounded-lg p-2" :placeholder="placeholder"
      :disabled="disabled"/>

    <ul v-if="isOpen && filteredOptions.length" class="absolute z-10 mt-1 w-full bg-inputbg border-borderblackui rounded-lg shadow-lg max-h-60 overflow-auto">
      <li v-for="(option, index) in filteredOptions" :key="option.id" @click="selectOption(option)"
        @mouseover="highlightedIndex = index" :class="[
          'p-2 cursor-pointer',
          highlightedIndex === index ? 'bg-blue-500 text-white' : ''
        ]">
        {{ option.name }}
      </li>
    </ul>

    <p v-if="isOpen && !filteredOptions.length" class="p-2 text-gray-500">
      No results found.
    </p>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number, null],
    default: null,
  },
  options: {
    type: Array,
    required: true,
  },
  placeholder: {
    type: String,
    default: 'Search...',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const query = ref('')
const isOpen = ref(false)
const highlightedIndex = ref(-1)

const filteredOptions = computed(() =>
  props.options.filter(option =>
    option.name.toLowerCase().includes(query.value.toLowerCase())
  )
)

const selectOption = (option) => {
  query.value = option.name
  emit('update:modelValue', option.id)
  nextTick(() => {
    isOpen.value = false
  })
}

const moveSelection = (direction) => {
  if (!filteredOptions.value.length) return
  highlightedIndex.value =
    (highlightedIndex.value + direction + filteredOptions.value.length) %
    filteredOptions.value.length
}

const closeDropdown = () => {
  isOpen.value = false
}

const handleFocusOut = (event) => {
  // If focus moves outside the combobox, close it
  if (!event.currentTarget.contains(event.relatedTarget)) {
    isOpen.value = false
    highlightedIndex.value = -1
  }
}


watch(
  () => props.modelValue,
  (newVal) => {
    const selected = props.options.find(opt => opt.id === newVal)
    if (selected) {
      query.value = selected.name
    } else {
      query.value = ''
    }
  },
  { immediate: true }
)

watch(
  () => [props.modelValue, props.options],
  () => {
    if (props.modelValue && props.options.length) {
      const selected = props.options.find(o => o.id === props.modelValue)
      if (selected) {
        query.value = selected.name
      }
    }
  },
  { immediate: true }
)


</script>

<style scoped>
</style>
