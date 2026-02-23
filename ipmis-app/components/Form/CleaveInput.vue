<template>
    <div v-bind="$attrs">
        <FormLabel :labelFor="labelFor" :class="labelClass">{{ label }}</FormLabel>

        <div class="mt-2">
            <input ref="inputRef" :id="labelFor" :name="name" :type="type" :autocomplete="autocomplete"
                :required="required" :placeholder="placeholder" :class="['form-input', inputClass]" :value="model"
                @input="onInput" @blur="$emit('blur', $event)"
                class="block w-full rounded-md bg-coldgrey px-3 p-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-jetblack placeholder:text-gray-500 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-inputfocusborder sm:text-sm/6" />
        </div>

        <p class="mt-2 text-sm text-red-600" v-if="errorMessage">{{ errorMessage }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Cleave from 'cleave.js'

const props = defineProps({
    labelFor: String,
    label: String,
    name: String,
    type: {
        type: String,
        default: 'text',
    },
    required: Boolean,
    placeholder: String,
    autocomplete: String,
    inputClass: String,
    labelClass: String,
    errorMessage: {
        type: String,
        default: '',
    },
    options: {
        type: Object,
        default: () => ({
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
        }),
    },
})

const emit = defineEmits(['update:modelValue', 'blur'])
const model = defineModel()

const inputRef = ref(null)
let cleaveInstance = null

onMounted(() => {
    if (inputRef.value) {
        cleaveInstance = new Cleave(inputRef.value, {
            ...props.options,
            onValueChanged: (e) => {
                emit('update:modelValue', e.target.value)
            },
        })
    }
})

onBeforeUnmount(() => {
    if (cleaveInstance) {
        cleaveInstance.destroy()
        cleaveInstance = null
    }
})

function onInput(event) {
    emit('update:modelValue', event.target.value)
}
</script>
