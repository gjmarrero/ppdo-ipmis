<template>
    <MetaTags title="Plantillas" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
            </div>
            <SettingsPlantilla v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :plantillaToEdit="selectedPlantilla" @settingAdded="handleSettingAddition"  />
            <DataTable :columns="columns" :data="plantillas" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()
const { plantillas, fetchPlantillas } = usePlantillas()
const tableKey = ref(0)

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

const openDialog = () => {
    selectedPlantilla.value = null
    isSettingsAddDialogOpen.value = true
}

const selectedPlantilla = ref(null)

const handleEdit = async (plantilla) => {
    selectedPlantilla.value = plantilla
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const isSettingsAddDialogOpen = ref(false)

const handleSettingAddition = (response) => {
    if(!response) return

    const updatedPlantilla = response.data.data
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