<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Specific Scope of Work' : 'Add Scope of Work' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <FormGroup v-model="scopeOfWorkForm.scope" :errorMessage="errorBag.scope" label="Description"
                    name="scope" type="text" required labelFor="scope" class="my-5" />
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
const { isSubmitting, scopeOfWorkForm, submitScopeOfWorkForm, resetForm } = useSpecificScopeOfWorks()
const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    scopeOfWorkToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.scopeOfWorkToEdit)

const handleSubmit = async () => {
    const data = await submitScopeOfWorkForm({
        scopeOfWorkToEdit: props.scopeOfWorkToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

watch(() => props.scopeOfWorkToEdit, (newVal) => {
    if (newVal) {
        scopeOfWorkForm.scope = newVal.scope
    } else {
        scopeOfWorkForm.scope = ''
    }
}, { immediate: true })

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})

</script>
