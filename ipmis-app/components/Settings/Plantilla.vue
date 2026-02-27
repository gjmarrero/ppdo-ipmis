<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>

    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{(dialogTitle.toLowerCase().replace(/\b\w/g, c => c.toUpperCase()))}}</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'plantilla'">
                <form @submit.prevent="handlePlantillaFormSubmit">
                    <div class="mb-2">
                        <FormLabel>Office-Division</FormLabel>
                        <FormCombobox :options="odsus" v-model="plantillaForm.odsu_id" />
                    </div>
                    <div class="mb-2">
                        <FormLabel>Position</FormLabel>
                        <FormCombobox :options="filteredPositions" v-model="plantillaForm.position_id" />
                    </div>
                    <div class="mb-2">
                        <FormLabel>Employee</FormLabel>
                        <FormCombobox :options="allEmployees" v-model="plantillaForm.employee_id" />
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

const {allEmployees, fetchAllEmployees} = useEmployees()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    settingsType: String,
    mode: {
        type: String,
        default: null,
    },
    plantillaToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const plantillaForm = reactive({
    'odsu_id': '',
    'position_id': '',
    'employee_id': ''
})

const resetForm = () => {
    plantillaForm.odsu_id = ''
    plantillaForm.position_id = ''
    plantillaForm.employee_id = ''
}
const loading = ref(false)

const handlePlantillaFormSubmit = async () => {
    loading.value = true
    try {
        let response
        if (props.plantillaToEdit) {
            response = await api.put(`/api/plantilla/${props.plantillaToEdit.id}`, plantillaForm)
        } else {
            response = await api.post('/api/plantilla', plantillaForm)
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

const positions = ref([])
const filteredPositions = ref([])
const fetchPositions = async () => {
    const response = await api.get('/api/fetchPositions')
    positions.value = response.data
}

// const employees = ref([])

// const fetchEmployees = async () => {
//     const response = await api.get('/api/fetchEmployees')
//     employees.value = response.data
// }

watch(() => plantillaForm.odsu_id, (newVal) => {
    if (newVal) {
        filteredPositions.value = positions.value.filter(p => p.odsu_id === newVal)
    }
})

watch(() => props.plantillaToEdit, (newVal) => {
    filteredPositions.value = positions.value.filter(p => p.odsu_id === newVal)
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

const dialogTitle = ref('add')

watch(() => props.mode, (newVal) => {
    if(newVal) {
        dialogTitle.value = newVal
    }
})

onMounted(() => {
    fetchPositions()
    fetchAllEmployees()
    fetchOdsus()
})
</script>
