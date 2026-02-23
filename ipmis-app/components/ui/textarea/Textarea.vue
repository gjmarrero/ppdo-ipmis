<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  class?: HTMLAttributes['class']
  defaultValue?: string | number
  modelValue?: string | number
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <textarea v-model="modelValue"
    :class="cn('flex min-h-20 w-full text-black rounded-md border border-input bg-coldgrey px-3 py-2 text-sm outline outline-1 -outline-offset-1 outline-jetblack placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-jetblack disabled:cursor-not-allowed disabled:opacity-50', props.class)" />
</template>
