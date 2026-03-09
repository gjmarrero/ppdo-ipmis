<template>
    <Dialog :open="props.isSettingsAddDialogOpen" @update:open="(val) => emit('update:isSettingsAddDialogOpen', val)">
        <DialogContent class="bg-dialogbg text-textsecondary">
            <DialogHeader>
                <DialogTitle>{{ isEditing ? 'Edit Employee' : 'Add Employee' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <FormGroup v-model="employeeForm.last_name" :errorMessage="errorBag.last_name" label="Last Name"
                    name="last_name" type="text" required labelFor="last_name" class="my-5" />

                <FormGroup v-model="employeeForm.first_name" :errorMessage="errorBag.first_name" label="First Name"
                    name="first_name" type="text" required labelFor="first_name" class="my-5" />

                <FormGroup v-model="employeeForm.middle_name" :errorMessage="errorBag.middle_name" label="Middle Name"
                    name="middle_name" type="text" required labelFor="middle_name" class="my-5" />

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

const { errorBag } = useAuth()
const { employeeForm, resetForm, isSubmitting, submitEmployeeForm } = useEmployees()
const props = defineProps({
    isSettingsAddDialogOpen: Boolean,
    employeeToEdit: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['update:isSettingsAddDialogOpen', 'settingAdded'])

const isEditing = computed(() => !!props.employeeToEdit)

const handleSubmit = async () => {
    const data = await submitEmployeeForm({
        employeeToEdit: props.employeeToEdit,
        onSuccess: () => {
            emit('form-submitted')
            emit('update:isSettingsAddDialogOpen', false)
        }
    })
    emit('settingAdded', data)
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

watch(() => props.isSettingsAddDialogOpen, (isOpen) => {
    if (!isOpen) {
        resetForm()
    }
})
</script>
