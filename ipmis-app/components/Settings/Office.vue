<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary border border-drawerborder">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Office' : 'Add Office' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div class="mb-8">
                    <FormGroup placeholder="Office" v-model="officeForm.office" name="office" label="Office name" />
                </div>
                <div class="mb-8">
                    <FormGroup placeholder="Office Acc" v-model="officeForm.office_acc" name="office_acc"
                        label="Office acc" />
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

const { officeForm, submitOfficeForm, resetForm, isSubmitting } = useOffices()

const { errorBag } = useCustomError()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    officeToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.officeToEdit)

const handleSubmit = async () => {
    const data = await submitOfficeForm({
        officeToEdit: props.officeToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

watch(() => props.officeToEdit, (newVal) => {
    if (newVal) {
        officeForm.office = newVal.office
        officeForm.office_acc = newVal.office_acc
    } else {
        officeForm.office = ''
        officeForm.office_acc = ''
    }
}, { immediate: true })

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})

</script>
