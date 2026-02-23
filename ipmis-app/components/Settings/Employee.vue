<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>

    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary">
            <DialogHeader>
                <DialogTitle>Add</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'employee'">
                <form @submit.prevent="handleEmployeeFormSubmit">
                    <FormGroup v-model="employeeForm.last_name" :errorMessage="errorBag.last_name" label="Last Name"
                        name="last_name" type="text" required labelFor="last_name" class="my-5" />

                    <FormGroup v-model="employeeForm.first_name" :errorMessage="errorBag.first_name" label="First Name"
                        name="first_name" type="text" required labelFor="first_name" class="my-5" />

                    <FormGroup v-model="employeeForm.middle_name" :errorMessage="errorBag.middle_name" label="Middle Name"
                        name="middle_name" type="text" required labelFor="middle_name" class="my-5" />

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
    employeeToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const employeeForm = reactive({
    'last_name': '',
    'first_name': '',
    'middle_name': '',
})

const resetForm = () => {
    employeeForm.last_name = ''
    employeeForm.first_name = ''
    employeeForm.middle_name = ''
}
const loading = ref(false)

const handleEmployeeFormSubmit = async () => {
    loading.value = true
    try {
        let response
        if (props.employeeToEdit) {
            response = await api.put(`/api/employee/${props.employeeToEdit.id}`, employeeForm)
        } else {
            response = await api.post('/api/employee', employeeForm)
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

watch(() => props.employeeToEdit, (newVal) => {
    if (newVal) {
        employeeForm.last_name = newVal.last_name
        employeeForm.first_name = newVal.first_name
        employeeForm.middle_name = newVal.middle_name
    } else {
        employeeForm.last_name = ''
        employeeForm.first_name = ''
        employeeForm.middle_name = ''
    }
}, { immediate: true })

onMounted(() => {
})
</script>
