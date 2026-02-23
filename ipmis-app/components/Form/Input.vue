<template>
  <input
    ref="inputRef"
    v-bind="$attrs"
    v-model="model"
    :class="[
      'block w-full rounded-md bg-coldgrey px-3 p-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-jetblack placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-inputfocusborder sm:text-sm/6',
      customClass
    ]"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Cleave from 'cleave.js'

const props = defineProps({
  class: { type: String, default: '' },
  mask: { type: [Boolean, Object], default: false },
  pn_mask: { type: [Boolean, Object], default: false },
})

const model = defineModel()
const inputRef = ref(null)
const customClass = props.class

onMounted(() => {
  let options = null

  if (props.mask) {
    options =
      typeof props.mask === 'object'
        ? props.mask
        : {
            delimiter: '-',
            blocks: [2, 4, 3],
            numericOnly: true,
          }
  } else if (props.pn_mask) {
    options =
      typeof props.pn_mask === 'object'
        ? props.pn_mask
        : {
            delimiter: '-', // ✅ FIXED
            blocks: [4, 2, 3],
            numericOnly: true,
          }
  }

  if (options) {
    const cleave = new Cleave(inputRef.value, options)
    inputRef.value.addEventListener('input', () => {
      model.value = cleave.getFormattedValue()
    })
  }
})
</script>
