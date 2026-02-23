<template>
    <div class="w-full mt-2" ref="comboboxWrapper" tabindex="-1" @focusin="isOpen = true" @focusout="handleFocusOut">
        <!-- Search Input -->
        <div class="relative">
            <FormInput v-model="query" @focus="isOpen = true" @keydown.arrow-down.prevent="moveSelection(1)"
                @keydown.arrow-up.prevent="moveSelection(-1)"
                @keydown.enter.prevent="selectOption(filteredOptions[highlightedIndex])" @keydown.esc="closeDropdown"
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                :placeholder="placeholder" :disabled="disabled" />

            <!-- Selected Items -->
            <div class="flex flex-wrap mt-2">
                <span v-for="(selected, index) in selectedItems" :key="selected.id"
                    class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2">
                    {{ selected.name }}
                    <button @click="removeSelected(index)" class="ml-1 text-xs text-white">
                        &times;
                    </button>
                </span>
            </div>
        </div>

        <!-- Dropdown List -->
        <ul v-if="isOpen && filteredOptions.length"
            class="absolute z-10 mt-1 w-64 bg-inputbg border rounded-lg shadow-lg">
            <li v-for="(option, index) in filteredOptions" :key="option.id" @click="toggleSelection(option)"
                @mouseover="highlightedIndex = index" :class="[
                    'p-2 cursor-pointer',
                    highlightedIndex === index ? 'bg-blue-500 text-secondarytext' : '',
                    selectedItems.some(item => item.id === option.id) ? 'bg-blue-100' : ''
                ]">
                <input type="checkbox" :checked="selectedItems.some(item => item.id === option.id)" class="mr-2" />
                {{ option.name }}
            </li>
        </ul>

        <!-- No Results Message -->
        <p v-if="isOpen && !filteredOptions.length" class="p-2 text-gray-500">
            No results found.
        </p>
    </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue';

const comboboxWrapper = ref(null)

const handleClickOutside = (event) => {
    if (comboboxWrapper.value && !comboboxWrapper.value.contains(event.target)) {
        isOpen.value = false; // Close dropdown if click is outside the wrapper
    }
};

// Bind event listener to detect click outside
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

// Clean up event listener
onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
// Props definition
const props = defineProps({
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
    modelValue: {
        type: Array,
        default: () => [],
    },

});

// Emit event
const emit = defineEmits(['update:modelValue']);


// Reactive state
const query = ref('');
const isOpen = ref(false);
const highlightedIndex = ref(-1);
const selectedItems = ref([]); // Store selected items

// Filtered options based on query
const filteredOptions = computed(() =>
    props.options.filter((option) =>
        option.name.toLowerCase().includes(query.value.toLowerCase())
    )
);

// Handle selection (toggle selection)
const toggleSelection = (option) => {
    const index = selectedItems.value.findIndex(item => item.id === option.id);

    if (index === -1) {
        selectedItems.value.push(option); // Add to selected items
    } else {
        selectedItems.value.splice(index, 1); // Remove from selected items
    }

    emit('update:modelValue', selectedItems.value.map(item => item.id)); // Emit selected item ids
};

// Handle removal of selected item
const removeSelected = (index) => {
    selectedItems.value.splice(index, 1);
    emit('update:modelValue', selectedItems.value.map(item => item.id)); // Emit updated selected ids
};

// Keyboard navigation
const moveSelection = (direction) => {
    if (!filteredOptions.value.length) return;
    highlightedIndex.value =
        (highlightedIndex.value + direction + filteredOptions.value.length) %
        filteredOptions.value.length;
};

// Close dropdown
const closeDropdown = () => {
    isOpen.value = false;
};

const handleFocusOut = (event) => {
    if (!comboboxWrapper.value?.contains(event.relatedTarget)) {
        isOpen.value = false
        highlightedIndex.value = -1
    }
}


// Watch query changes
watch(query, (newValue) => {
    isOpen.value = newValue.trim() !== ''; // Open dropdown when query is not empty
});

// Sync selected items whenever modelValue changes
watch(
    () => props.modelValue,
    (newVal) => {
        if (Array.isArray(newVal)) {
            selectedItems.value = props.options.filter(option =>
                newVal.includes(option.id)
            );
        }
    },
    { immediate: true }
);

// Also resync when options are loaded or updated
watch(
    () => props.options,
    (newOptions) => {
        if (Array.isArray(props.modelValue)) {
            selectedItems.value = newOptions.filter(option =>
                props.modelValue.includes(option.id)
            );
        }
    },
    { immediate: true, deep: true }
);

</script>

<style scoped>
/* Add custom styles here if needed */
</style>