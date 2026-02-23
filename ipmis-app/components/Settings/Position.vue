<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>

    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary">
            <DialogHeader>
                <DialogTitle>Add</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'position'">
                <form @submit.prevent="handlePositionFormSubmit">
                    <div class="mb-2">
                        <FormLabel>Office/Division</FormLabel>
                        <FormCombobox :options="odsus" v-model="positionForm.odsu_id" />
                    </div>
                    <FormGroup v-model="positionForm.title" :errorMessage="errorBag.title"
                        label="Title" name="title" type="text"
                        required labelFor="title" class="my-5" />

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
    positionToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const positionForm = reactive({
    'odsu_id': '',
    'title': ''
})

const resetForm = () => {
    positionForm.odsu_id = ''
    positionForm.title = ''
}
const loading = ref(false)

const handlePositionFormSubmit = async () => {
    loading.value = true
    try {
        let response
        if (props.positionToEdit) {
            response = await api.put(`/api/position/${props.positionToEdit.id}`, positionForm)
        } else {
            response = await api.post('/api/position', positionForm)
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

const odsus = ref([])

const fetchOdsus = async () => {
    const response = await api.get('/api/fetchOdsus')
    odsus.value = response.data
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

onMounted(() => {
    fetchOdsus()
})
</script>
