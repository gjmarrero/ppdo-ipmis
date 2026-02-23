<template>
    <div v-bind="$attrs">
        <FormLabel :labelFor="labelFor" :class="labelClass">{{ label }}</FormLabel>

        <div class="mt-2">
            <input type="text" :name="name" :id="labelFor" :placeholder="placeholder" :required="required"
                :autocomplete="autocomplete" :class="inputClass" :value="displayValue" @input="handleInput"
                @blur="handleBlur"
                class="block w-full rounded-md bg-coldgrey px-3 p-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-jetblack placeholder:text-jetblack focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-jetblack sm:text-sm/6" />
        </div>

        <p class="mt-2 text-sm text-red-600" v-if="errorMessage">
            {{ errorMessage }}
        </p>
    </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'

const props = defineProps({
    labelFor: String,
    label: String,
    name: String,
    required: Boolean,
    placeholder: String,
    autocomplete: String,
    inputClass: String,
    labelClass: String,
    errorMessage: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue', 'blur'])
const model = defineModel()

const displayValue = ref('')

function handleInput(e) {
    displayValue.value = e.target.value.replace(/[^0-9.]/g, '') // allow only digits + .
}

function handleBlur() {
    const num = parseFloat(displayValue.value)
    if (!isNaN(num)) {
        displayValue.value = num.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })
        model.value = num
    }
}
</script>
