<template>
    <div class="flex flex-row justify-end">
        <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
    </div>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>Add</DialogTitle>
            </DialogHeader>
            <div v-if="settingsType === 'user'">
                <form @submit.prevent="handleUserFormSubmit">
                    <div class="mb-2">
                        <FormLabel>Employee Name</FormLabel>
                        <FormCombobox :options="employees" v-model="userForm.employee_id" />
                    </div>
                    <FormGroup v-model="userForm.name" :errorMessage="errorBag.name" label="User name" name="name"
                        type="text" autocomplete="name" required labelFor="name" />

                    <FormGroup v-model="userForm.email" :errorMessage="errorBag.email" label="Email Address"
                        name="email" type="email" autocomplete="email" required labelFor="email" />

                    <div class="mb-2">
                        <FormGroup v-model="userForm.password" :errorMessage="errorBag.password" label="Password"
                            name="password" type="password" autocomplete="current-password" required
                            labelFor="password" />

                        <FormGroup v-model="userForm.password_confirmation"
                            :errorMessage="errorBag.password_confirmation" label="Password Confirmation"
                            name="password_confirmation" type="password" autocomplete="current-password" required
                            labelFor="password" />
                    </div>
                    <div class="mb-4">
                        <FormLabel>Role</FormLabel>
                        <FormCombobox :options="roleOptions" v-model="userForm.role" />
                    </div>
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
    userToEdit: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const openDialog = () => {
    emit('update:isSettingsAddDialogOpen', true)
}

const userForm = reactive({
    'employee_id': '',
    'name': '',
    'email': '',
    'password': '',
    'password_confirmation': '',
    'role': ''
})

const roleOptions = [
    { id: 'ppdoDevChief', name: 'PPDO Project Dev Chief' },
    { id: 'ppdoDevStaff', name: 'PPDO Project Dev Staff' },
    { id: 'ppdoMonitoringChief', name: 'PPDO Project Monitoring Chief' },
    { id: 'ppdoMonitoringStaff', name: 'PPDO Project Monitoring Staff' },
    { id: 'peoPlanningChief', name: 'PEO Planning Chief' },
    { id: 'peoPlanningStaff', name: 'PEO Planning Staff' },
    { id: 'benroChief', name: 'BENRO Division Chief' },
    { id: 'benroStaff', name: 'BENRO Staff' },
    { id: 'peoConstructionChief', name: 'PEO Construction Chief' },
    { id: 'peoConstructionStaff', name: 'PEO Construction Staff' },
]

const loading = ref(false)

const handleUserFormSubmit = async () => {
    console.log("Form", userForm)
    loading.value = true
    try {
        let response
        if (props.userToEdit) {
            response = await api.put(`/api/user/${props.userToEdit.id}`, userForm)
        } else {
            response = await api.post('/api/user', userForm)
        }
        emit('update:isSettingsAddDialogOpen', false)
        emit('settingAdded', response.data.user)
    } catch (error) {
        console.error('Save failed:', error)
    } finally {
        loading.value = false
    }

}

const employees = ref([])

const fetchEmployees = async () => {
    const response = await api.get('/api/fetchEmployees')
    employees.value = response.data
}

const default_employee = ref(null)

watch(() => props.userToEdit, (newVal) => {
    console.log("For edit", newVal)
    if (newVal) {
        userForm.email = newVal.email

        const selected = employees.value.find(employee => employee.id === newVal.employee_id)
        if (selected) {
            default_employee.value = selected
        }
        userForm.employee_id = selected
        userForm.name = newVal.name
        userForm.password = ''
        userForm.password_confirmation = ''
    }
}, { immediate: true })

onMounted(() => {
    fetchEmployees()
})
</script>
