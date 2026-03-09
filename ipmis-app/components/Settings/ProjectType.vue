<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary border border-drawerborder">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Project Type' : 'Add Project Type' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <FormGroup v-model="projectTypeForm.project_type" :errorMessage="errorBag.project_type"
                    label="Project Type" name="project_type" type="text" required labelFor="project_type"
                    class="my-5" />

                <FormGroup v-model="projectTypeForm.project_type_code" :errorMessage="errorBag.project_type_code"
                    label="Project Type Code" name="project_type_code" type="text" required labelFor="project_type_code"
                    class="my-5" />

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

const { projectTypeForm, submitProjectTypeForm, resetForm, isSubmitting } = useProjectTypes()

const { errorBag } = useCustomError()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    projectTypeToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.projectTypeToEdit)

const handleSubmit = async () => {
    const data = await submitProjectTypeForm({
        projectTypeToEdit: props.projectTypeToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    console.log("emit this data", data)
    emit('settingAdded', data)
}

watch(() => props.projectTypeToEdit, (newVal) => {
    if (newVal) {
        projectTypeForm.project_type = newVal.name
        projectTypeForm.project_type_code = newVal.project_type_code
    } else {
        projectTypeForm.project_type = ''
        projectTypeForm.project_type_code = ''
    }
}, { immediate: true })

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})

onMounted(() => {
})
</script>
