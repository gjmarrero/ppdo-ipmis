<template>
    <div
        class="flex items-center outline outline-1 -outline-offset-1 outline-jetblack border rounded-md overflow-hidden">
        <!-- Locked prefix -->
        <span class="px-2 py-2 bg-gray-100 text-gray-700 font-mono border-r select-none whitespace-nowrap">
            {{ prefix }}
        </span>

        <!-- Editable suffix -->
        <input ref="inputRef" type="text" v-model="suffix" maxlength="3" inputmode="numeric" pattern="\d*"
            class="flex-1 px-2 py-2 font-mono focus:outline-none bg-coldgrey" @input="updateValue" @blur="handleBlur" :readonly="readonly" />
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue"

const props = defineProps({
    prefix: { type: String, required: true },
    modelValue: { type: String, default: "" },
    readonly: { type: Boolean, default: false }
})

const emit = defineEmits(["update:modelValue", "blur"])

const suffix = ref(props.modelValue.split("-").at(-1)?.replace(/\D/g, "") || "")

const fullValue = computed(() => `${props.prefix}-${suffix.value}`)

watch(fullValue, (val) => emit("update:modelValue", val))

watch(() => props.modelValue, (val) => {
    const parts = val.split("-")
    suffix.value = parts.at(-1)?.replace(/\D/g, "") || ""
})

const updateValue = () => {
    suffix.value = suffix.value.replace(/\D/g, "").slice(0, 3)
}

const padSuffix = () => {
    suffix.value = suffix.value ? suffix.value.padStart(3, "0") : "000"
}

// 🔹 Forward blur event to parent
const handleBlur = (e) => {
    padSuffix()            // keep existing behavior
    emit("blur", e)        // forward to parent so @blur works
}
</script>
