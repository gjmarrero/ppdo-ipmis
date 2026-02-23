<template>
    <div v-bind="$attrs">
        <FormLabel :labelFor="labelFor" :class="labelClass">
            {{ label }}
        </FormLabel>

        <div class="mt-2">
            <!-- If a slot is provided, use it -->
            <slot v-if="$slots.default" />

            <!-- Otherwise fallback to FormInput -->
            <FormInput v-else v-model="model" :name="name" :type="type" :autocomplete="autocomplete"
                :required="required" :placeholder="placeholder" :class="inputClass" @blur="$emit('blur', $event)" />
        </div>

        <p v-if="errorMessage" class="mt-2 text-sm text-red-600">
            {{ errorMessage }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    labelFor: String,
    label: String,
    name: String,
    type: String,
    required: Boolean,
    placeholder: String,
    autocomplete: String,
    inputClass: String,
    labelClass: String,
    errorMessage: {
        type: String,
        default: '',
    },
})

defineEmits(['update:modelValue', 'blur'])

const model = defineModel()
// const model = computed({
//     get: () => props.modelValue,
//     set: val => emit('update:modelValue', val)
// })
</script>
