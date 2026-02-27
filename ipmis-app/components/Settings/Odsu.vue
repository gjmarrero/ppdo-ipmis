<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>

    <Dialog :autoFocus="false" :open="props.isSettingsAddDialogOpen"
        @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{(dialogTitle.toLowerCase().replace(/\b\w/g, c => c.toUpperCase()))}}</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'odsu'">
                <form @submit.prevent="handleOdsuFormSubmit">
                    <div class="mb-2">
                        <FormLabel>Office</FormLabel>
                        <FormCombobox :options="offices" v-model="odsuForm.office_id" />
                    </div>
                    <div class="mb-2">
                        <FormLabel>Division</FormLabel>
                        <FormCombobox :options="divisions" v-model="odsuForm.division_id" />
                    </div>
                    <div class="flex flex-row justify-end text-textprimary pt-4">
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
    odsuToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const odsuForm = reactive({
    'office_id': '',
    'division_id': ''
})

const resetForm = () => {
    odsuForm.office_id = ''
    odsuForm.division_id = ''
}
const loading = ref(false)

const handleOdsuFormSubmit = async () => {
    loading.value = true
    try {
        let response
        if (props.odsuToEdit) {
            response = await api.put(`/api/odsu/${props.odsuToEdit.id}`, odsuForm)
        } else {
            response = await api.post('/api/odsu', odsuForm)
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

const offices = ref([])

const fetchOffices = async () => {
    const response = await api.get('/api/fetchOffices')
    offices.value = response.data
}

const divisions = ref([])

const fetchDivisions = async () => {
    const response = await api.get('/api/fetchDivisions')
    divisions.value = response.data
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

const dialogTitle = ref('add')

watch(() => props.mode, (newVal) => {
    if(newVal) {
        dialogTitle.value = newVal
    }
})

onMounted(() => {
    fetchOffices()
    fetchDivisions()
})
</script>
