<template>
    <Dialog :autoFocus="false" :open="props.isSettingsAddDialogOpen"
        @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Office-Division' : 'Add Office-Division' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div class="mb-2">
                    <FormLabel>Office</FormLabel>
                    <FormCombobox :options="officesAsSource" v-model="odsuForm.office_id" />
                </div>
                <div class="mb-2">
                    <FormLabel>Division</FormLabel>
                    <FormCombobox :options="divisions" v-model="odsuForm.division_id" />
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

const { divisions, fetchDivisions, odsuForm, submitOdsuForm, resetForm, isSubmitting } = useOdsus()
const { officesAsSource, fetchOfficesAsSource } = useOffices()
const { errorBag } = useCustomError()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    odsuToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.odsuToEdit)

const handleSubmit = async () => {
    const data = await submitOdsuForm({
        odsuToEdit: props.odsuToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

watch(() => props.odsuToEdit, (newVal) => {
    if (newVal) {
        odsuForm.office_id = newVal.office_id
        odsuForm.division_id = newVal.division_id
    } else {
        odsuForm.office_id = ''
        odsuForm.division_id = ''
    }
}, { immediate: true })

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})

onMounted(() => {
    fetchOfficesAsSource()
    fetchDivisions()
    console.log("Odsu to edit", props.odsuToEdit)
})
</script>
