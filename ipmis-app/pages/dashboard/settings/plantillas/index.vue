<template>
    <MetaTags title="Plantillas" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsPlantilla v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :plantillaToEdit="selectedPlantilla" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="plantillas" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const tableKey = ref(0)

const settingsType = ref('plantilla')

const handleView = (plantilla) => {
    console.log('View clicked: ', plantilla)
}

const handleDelete = async (plantilla) => {
    const response = await api.delete(`/api/plantilla/${plantilla.id}`)
    toast({
        description: response.data.message
    })
    fetchPlantillas()
}

const selectedPlantilla = ref(null)

const handleEdit = async (plantilla) => {
    selectedPlantilla.value = plantilla
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const plantillas = ref([])

const isSettingsAddDialogOpen = ref(false)

const fetchPlantillas = async () => {
    const response = await api.get('/api/plantillas')
    plantillas.value = response.data.data
}

const handleSettingAddition = (updatedPlantilla) => {
    console.log("Updated plantilla", updatedPlantilla)
    const index = plantillas.value.findIndex(o => o.id === updatedPlantilla.id)
    if (index !== -1) {
        plantillas.value.splice(index, 1, updatedPlantilla)
        plantillas.value = [...plantillas.value]
        tableKey.value++
    } else {
        plantillas.value = [...plantillas.value, { ...updatedPlantilla }]
    }
    selectedPlantilla.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}

onMounted(() => {
    fetchPlantillas()
})
</script>