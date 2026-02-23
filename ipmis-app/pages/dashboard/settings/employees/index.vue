<template>
    <MetaTags title="Employees" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsEmployee v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :employeeToEdit="selectedEmployee" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="allEmployees" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()
const { employees, allEmployees, fetchEmployees, fetchAllEmployees } = useEmployees()

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

const handleEdit = async (employee) => {
    selectedEmployee.value = employee
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const isSettingsAddDialogOpen = ref(false)

const handleSettingAddition = (updatedEmployee) => {
    console.log("Updated Emp", updatedEmployee)
    const index = allEmployees.value.findIndex(o => o.id === updatedEmployee.id)
    if (index !== -1) {
        allEmployees.value.splice(index, 1, updatedEmployee)
        allEmployees.value = [...allEmployees.value]
        tableKey.value++
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