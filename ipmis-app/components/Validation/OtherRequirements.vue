<template>
    <div>
        <div class="flex flex-wrap gap-4 mt-2">
            <div v-for="type in requirementTypes" :key="type.id" class="flex items-center space-x-2">
                <Checkbox :id="type.id" :checked="selectedRequirementTypes.includes(type.id)"
                    @update:checked="toggleRequirementType(type.id, $event)" />
                <label :for="type.id" class="text-sm text-gray-700">
                    {{ type.label }}
                </label>
            </div>
        </div>
    </div>
</template>

<script setup>

const {requirementTypes, selectedRequirementTypes} = useValidations()

const toggleRequirementType = (id, checked) => {
    if (checked) {
        if (!selectedRequirementTypes.value.includes(id)) {
            selectedRequirementTypes.value.push(id)
        }
    } else {
        selectedRequirementTypes.value = selectedRequirementTypes.value.filter(b => b !== id)
    }
}
</script>

<style lang="scss" scoped></style>