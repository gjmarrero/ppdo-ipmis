<template>
    <MetaTags title="Positions" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
            </div>
            <SettingsPosition v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :positionToEdit="selectedPosition" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="positions" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()
const { positions, fetchPositions } = usePositions()
const tableKey = ref(0)

const settingsType = ref('position')

const handleView = (position) => {
    console.log('View clicked: ', position)
}

const handleDelete = async (position) => {
    const response = await api.delete(`/api/position/${position.id}`)
    toast({
        description: response.data.message
    })
    fetchPositions()
}

const openDialog = () => {
    selectedPosition.value = null
    isSettingsAddDialogOpen.value = true
}

const selectedPosition = ref(null)

const mode = ref('add')

const handleEdit = async (position) => {
    selectedPosition.value = position
    mode.value = 'edit'
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const isSettingsAddDialogOpen = ref(false)

const handleSettingAddition = (response) => {
    if(!response) return
    
    const updatedPosition = response.data.data

    const index = positions.value.findIndex(o => o.id === updatedPosition.id)
    if (index !== -1) {
        positions.value.splice(index, 1, updatedPosition)
        positions.value = [...positions.value]
        tableKey.value++
    } else {
        positions.value = [...positions.value, { ...updatedPosition }]
    }
    selectedPosition.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}

onMounted(() => {
    fetchPositions()
})
</script>