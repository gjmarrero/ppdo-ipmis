<template>
    <MetaTags title="Offices" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsAdd v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :officeToEdit="selectedOffice" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="offices" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const tableKey = ref(0)

const settingsType = ref('office')

const handleView = (office) => {
    console.log('View clicked: ', office)
}

const handleDelete = async (office) => {
    const response = await api.delete(`/api/office/${office.id}`)
    toast({
        description: response.data.message
    })
    fetchOffices()
}

const selectedOffice = ref(null)

const handleEdit = async (office) => {
    selectedOffice.value = office
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const offices = ref([])

const isSettingsAddDialogOpen = ref(false)

const fetchOffices = async () => {
    const response = await api.get('/api/offices')
    offices.value = response.data.data
}

const handleSettingAddition = (updatedOffice) => {
    const index = offices.value.findIndex(o => o.id === updatedOffice.id)
    if (index !== -1) {
        offices.value.splice(index, 1, updatedOffice)
        offices.value = [...offices.value]
        tableKey.value++
    } else {
        offices.value = [...offices.value, { ...updatedOffice }]
    }
    selectedOffice.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}

onMounted(() => {
    fetchOffices()
})
</script>