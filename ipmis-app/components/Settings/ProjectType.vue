<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>

    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary border border-drawerborder">
            <DialogHeader>
                <DialogTitle>Add</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'project_type'">
                <form @submit.prevent="handleSubmit">
                    <FormGroup v-model="projectTypeForm.project_type" :errorMessage="errorBag.project_type"
                        label="Project Type" name="project_type" type="text" required labelFor="project_type"
                        class="my-5" />

                    <FormGroup v-model="projectTypeForm.project_type_code" :errorMessage="errorBag.project_type_code"
                        label="Project Type Code" name="project_type_code" type="text" required
                        labelFor="project_type_code" class="my-5" />

                    <div class="flex flex-row justify-end text-textprimary">
                        <Button type="submit" variant="newprimary" :disabled="loading">Submit</Button>
                    </div>
                </form>
            </div>
        </DialogContent>
    </Dialog>
</template>

<script setup>

const { projectTypeForm, submitProjectTypeForm } = useProjectTypes()
const { api } = useAxios()

const { errorBag } = useAuth()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    settingsType: String,
    projectTypeToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const handleSubmit = async () => {
    const data = await submitProjectTypeForm({
        projectTypeToEdit: props.projectTypeToEdit,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

// watch(() => props.positionToEdit, (newVal) => {
//     if (newVal) {
//         positionForm.odsu_id = newVal.odsu_id
//         positionForm.title = newVal.title
//     } else {
//         positionForm.odsu_id = ''
//         positionForm.title = ''
//     }
// }, { immediate: true })

onMounted(() => {
})
</script>
