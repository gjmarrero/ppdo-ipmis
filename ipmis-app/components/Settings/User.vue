<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit User' : 'Add User' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div class="mb-2">
                    <FormLabel>Employee Name</FormLabel>
                    <FormCombobox :options="employees" v-model="userForm.employee_id" />
                </div>
                <FormGroup v-model="userForm.name" :errorMessage="errorBag.name" label="User name" name="name"
                    type="text" autocomplete="name" required labelFor="name" />

                <FormGroup v-model="userForm.email" :errorMessage="errorBag.email" label="Email Address" name="email"
                    type="email" autocomplete="email" required labelFor="email" />

                <div class="mb-2">
                    <FormGroup v-model="userForm.password" :errorMessage="errorBag.password" label="Password"
                        name="password" type="password" autocomplete="current-password" required labelFor="password" />

                    <FormGroup v-model="userForm.password_confirmation" :errorMessage="errorBag.password_confirmation"
                        label="Password Confirmation" name="password_confirmation" type="password"
                        autocomplete="current-password" required labelFor="password" />
                </div>
                <div class="mb-4">
                    <FormLabel>Role</FormLabel>
                    <FormCombobox :options="roleOptions" v-model="userForm.role" />
                </div>
                <div class="flex flex-row justify-end text-textprimary">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                        <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
            <p v-if="errorBag.general" class="text-red-600 mb-4">
                {{ errorBag.general }}
            </p>
        </DialogContent>
    </Dialog>
</template>

<script setup>

const { errorBag, resetErrorBag } = useCustomError()
const { roleOptions, userForm, resetForm, submitUserForm, isSubmitting } = useUsers()
const { employees, fetchEmployees } = useEmployees()

const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    userToEdit: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.userToEdit)

const handleSubmit = async () => {
    const data = await submitUserForm({
        userToEdit: props.userToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
}

const default_employee = ref(null)
const default_role = ref(null)

watch(() => props.userToEdit, (newVal) => {
    if (newVal) {
        const selected = employees.value.find(employee => employee.id === newVal.employee_id)
        if (selected) {
            default_employee.value = selected.id
        }

        const selectedRole = roleOptions.find(role => role.id === newVal.role)
        if (selectedRole) {
            default_role.value = selectedRole.id
        }
        userForm.employee_id = default_employee.value
        userForm.name = newVal.name
        userForm.email = newVal.email
        userForm.password = ''
        userForm.password_confirmation = ''
        userForm.role = default_role.value
    }
}, { immediate: true })

const dialogTitle = ref('add')

watch(() => props.mode, (newVal) => {
    if (newVal) {
        dialogTitle.value = newVal
    }
})

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (isOpen) {
        resetErrorBag()
    } else {
        resetForm()
        resetErrorBag()
    }
})

onMounted(() => {
    fetchEmployees()
})
</script>
