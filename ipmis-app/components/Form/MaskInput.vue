<template>
  <input
    ref="inputRef"
    v-model="internalValue"
    type="text"
    :placeholder="placeholder"
    class="block w-full rounded-md bg-coldgrey px-3 p-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-jetblack placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-jetblack sm:text-sm/6"
    @input="handleInput"
  />
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import Cleave from 'cleave.js'

const props = defineProps({
  modelValue: String,
  mask: [Boolean, Object],
  pn_mask: [Boolean, Object],
  prefix: String, // 🧩 for pn_mask: e.g. "2025-DPWH1" | for mask: e.g. "DPWH1-2025"
})

const emit = defineEmits(['update:modelValue'])
const inputRef = ref(null)
const internalValue = ref(props.modelValue || '')
let cleave = null

const placeholder = computed(() =>
  props.pn_mask ? `${props.prefix}-###` : `${props.prefix}-###`
)

// sync parent
watch(internalValue, (val) => emit('update:modelValue', val))
watch(() => props.modelValue, (val) => (internalValue.value = val || ''))

onMounted(() => {
  const options = {
    delimiter: '-',
    blocks: [4, 8, 3],
    numericOnly: false,
  }
  cleave = new Cleave(inputRef.value, options)

  if (props.prefix) enforcePrefix()
})

watch(() => props.prefix, () => enforcePrefix())

function enforcePrefix() {
  const value = internalValue.value || ''
  const parts = value.split('-')
  const suffix = parts.at(-1) || ''
  const full = `${props.prefix}-${suffix}`

  internalValue.value = full
  if (inputRef.value) inputRef.value.value = full
}

// ✅ Main logic for both types
function handleInput(e) {
  const value = e.target.value.trim()
  const suffix = value.split('-').at(-1)?.replace(/\D/g, '').slice(0, 3) || ''

  const newVal = `${props.prefix}-${suffix}`
  internalValue.value = newVal
  e.target.value = newVal

  // move cursor to end
  const pos = newVal.length
  requestAnimationFrame(() => e.target.setSelectionRange(pos, pos))
}
</script>
