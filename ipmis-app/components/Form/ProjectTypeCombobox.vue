<script setup>
import { ref, computed, watch, nextTick } from 'vue'

// Props
const props = defineProps({
  options: {
    type: Array,
    required: true,
  },
  modelValue: {
    type: Object, // Default selected value
    default: null,
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

// Emit event
const emit = defineEmits(['update:modelValue'])

// Reactive state
const query = ref('')
const isOpen = ref(false)
const highlightedIndex = ref(-1)

// Set default value when component is mounted
watch(() => props.modelValue, (newValue) => {
  if (newValue) {
    query.value = newValue.fundsource // Set default value in input field
  }
}, { immediate: true }) // Run immediately when the component mounts

// Filtered options based on query
const filteredOptions = computed(() =>
  props.options.filter((option) =>
    option.fundsource.toLowerCase().includes(query.value.toLowerCase())
  )
)

// Handle selection
const selectOption = (option) => {
  query.value = option.fundsource // Update input field with selected option
  emit('update:modelValue', option) // Emit selected value
  
  // Close dropdown after the DOM updates
  nextTick(() => {
    isOpen.value = false
  })
}

// Keyboard navigation
const moveSelection = (direction) => {
  if (!filteredOptions.value.length) return
  highlightedIndex.value =
    (highlightedIndex.value + direction + filteredOptions.value.length) %
    filteredOptions.value.length
}

// Close dropdown
const closeDropdown = () => {
  isOpen.value = false
}

// Watch query changes
watch(query, (newValue) => {
  // isOpen.value = newValue.trim() !== ''
})
</script>

<template>
  <div class="w-full mt-2">
    <!-- Search Input -->
    <input
      v-model="query"
      @focus="isOpen = true"
      @keydown.arrow-down.prevent="moveSelection(1)"
      @keydown.arrow-up.prevent="moveSelection(-1)"
      @keydown.enter.prevent="selectOption(filteredOptions[highlightedIndex])"
      @keydown.esc="closeDropdown"
      class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
      :placeholder="placeholder"
      :disabled="disabled"
    />

    <!-- Dropdown List -->
    <ul
      v-if="isOpen && filteredOptions.length"
      class="absolute z-10 mt-1 w-64 bg-white border rounded-lg shadow-lg"
    >
      <li
        v-for="(option, index) in filteredOptions"
        :key="option.id"
        @click="selectOption(option)"
        @mouseover="highlightedIndex = index"
        :class="[ 'p-2 cursor-pointer', highlightedIndex === index ? 'bg-blue-500 text-white' : '' ]"
      >
        {{ option.fundsource }}
      </li>
    </ul>

    <!-- No Results Message -->
    <p v-if="isOpen && !filteredOptions.length" class="p-2 text-gray-500">
      No results found.
    </p>
  </div>
</template>
