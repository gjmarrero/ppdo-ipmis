<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Position' : 'Add Position' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div class="mb-2">
                    <FormLabel>Office/Division</FormLabel>
                    <FormCombobox :options="odsusAsSource" v-model="positionForm.odsu_id" />
                </div>
                <FormGroup v-model="positionForm.title" :errorMessage="errorBag.title" label="Title" name="title"
                    type="text" required labelFor="title" class="my-5" />

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

const { positionForm, resetForm, isSubmitting, submitPositionForm } = usePositions()
const { odsusAsSource, fetchOdsusAsSource } = useOdsus()

const { errorBag } = useAuth()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    positionToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.positionToEdit)

const handleSubmit = async () => {
    const data = await submitPositionForm({
        positionToEdit: props.positionToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

watch(() => props.positionToEdit, (newVal) => {
    if (newVal) {
        positionForm.odsu_id = newVal.odsu_id
        positionForm.title = newVal.title
    } else {
        positionForm.odsu_id = ''
        positionForm.title = ''
    }
}, { immediate: true })

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})

onMounted(() => {
    fetchOdsusAsSource()
})
</script>
