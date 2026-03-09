<template>
    <MetaTags title="Employees" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
            </div>
            <SettingsEmployee v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen"
                :employeeToEdit="selectedEmployee" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="allEmployees" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()
const { allEmployees, fetchAllEmployees } = useEmployees()

const tableKey = ref(0)

const settingsType = ref('employee')

const handleView = (employee) => {
    console.log('View clicked: ', employee)
}

const handleDelete = async (employee) => {
    const response = await api.delete(`/api/employee/${employee.id}`)
    toast({
        description: response.data.message
    })
    fetchAllEmployees()
}

const selectedEmployee = ref(null)

const mode = ref('add')

const handleEdit = async (employee) => {
    selectedEmployee.value = employee
    mode.value = 'edit'
    isSettingsAddDialogOpen.value = true
}

const openDialog = () => {
    selectedEmployee.value = null
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const isSettingsAddDialogOpen = ref(false)

const handleSettingAddition = (response) => {
    if (!response) return

    const updatedEmployee = response.data.data
    const index = allEmployees.value.findIndex(o => String(o.id) === String(updatedEmployee.id))

    if (index !== -1) {
        const updated = [...allEmployees.value]
        updated[index] = { ...updatedEmployee }
        allEmployees.value = updated
    } else {
        allEmployees.value = [...allEmployees.value, { ...updatedEmployee }]
    }
    selectedEmployee.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}

onMounted(() => {
    fetchAllEmployees()
})
</script>