<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>

    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{(dialogTitle.toLowerCase().replace(/\b\w/g, c => c.toUpperCase()))}}</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'scopeOfWork'">
                <form @submit.prevent="handleScopeOfWorkFormSubmit">
                    <FormGroup v-model="scopeOfWorkForm.scope" :errorMessage="errorBag.scope" label="Description" name="scope"
                        type="text" required labelFor="scope" class="my-5" />
                    <div class="flex flex-row justify-end text-textprimary">
                        <Button type="submit" variant="newprimary" :disabled="loading">Submit</Button>
                    </div>
                </form>
            </div>
        </DialogContent>
    </Dialog>
</template>

<script setup>

const { api } = useAxios()

const { errorBag } = useAuth()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    settingsType: String,
    mode: {
        type: String,
        default: 'add',
    },
    scopeOfWorkToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const scopeOfWorkForm = reactive({
    'scope': '',
})

const resetForm = () => {
    scopeOfWorkForm.scope = ''
}
const loading = ref(false)

const handleScopeOfWorkFormSubmit = async () => {
    loading.value = true
    try {
        let response
        if (props.scopeOfWorkToEdit) {
            response = await api.put(`/api/scope_of_work/${props.scopeOfWorkToEdit.id}`, scopeOfWorkForm)
        } else {
            response = await api.post('/api/scope_of_work', scopeOfWorkForm)
        }
        resetForm()
        emit('update:isSettingsAddDialogOpen', false)
        emit('settingAdded', response.data.data)
    } catch (error) {
        console.error('Save failed:', error)
    } finally {
        loading.value = false
    }
}

watch(() => props.scopeOfWorkToEdit, (newVal) => {
    if (newVal) {
        scopeOfWorkForm.scope = newVal.scope
    } else {
        scopeOfWorkForm.scope = ''
    }
}, { immediate: true })

const dialogTitle = ref('add')

watch(() => props.mode, (newVal) => {
    if (newVal) {
        dialogTitle.value = newVal
    }
})

</script>
