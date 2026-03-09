<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Plantilla' : 'Add Plantilla' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div class="mb-2">
                    <FormLabel>Office-Division</FormLabel>
                    <FormCombobox :options="odsusAsSource" v-model="plantillaForm.odsu_id" />
                </div>
                <div class="mb-2">
                    <FormLabel>Position</FormLabel>
                    <FormCombobox :options="filteredPositions" v-model="plantillaForm.position_id" />
                </div>
                <div class="mb-2">
                    <FormLabel>Employee</FormLabel>
                    <FormCombobox :options="allEmployees" v-model="plantillaForm.employee_id" />
                </div>
                <div class="flex flex-row justify-end text-textprimary">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                        <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>

<script setup>

const { errorBag } = useCustomError()

const { allEmployees, fetchAllEmployees } = useEmployees()
const { odsusAsSource, fetchOdsusAsSource } = useOdsus()
const { positionsAsSource, fetchPositionsAsSource, filteredPositions } = usePositions()
const { plantillaForm, resetForm, submitPlantillaForm, isSubmitting } = usePlantillas()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    plantillaToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.plantillaToEdit)

const handleSubmit = async () => {
    const data = await submitPlantillaForm({
        plantillaToEdit: props.plantillaToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

watch(() => plantillaForm.odsu_id, (newVal) => {
    if (newVal) {
        filteredPositions.value = positionsAsSource.value.filter(p => p.odsu_id === newVal)
    }
})

watch(() => props.plantillaToEdit, (newVal) => {
    filteredPositions.value = positionsAsSource.value.filter(p => p.odsu_id === newVal)
    if (newVal) {
        plantillaForm.odsu_id = newVal.odsu
        plantillaForm.position_id = newVal.position_id
        plantillaForm.employee_id = newVal.employee_id
    } else {
        plantillaForm.odsu_id = ''
        plantillaForm.position_id = ''
        plantillaForm.employee_id = ''
    }
}, { immediate: true })

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})

onMounted(() => {
    fetchPositionsAsSource()
    fetchAllEmployees()
    fetchOdsusAsSource()
})
</script>
