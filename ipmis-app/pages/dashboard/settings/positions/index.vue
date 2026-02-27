<template>
    <MetaTags title="Positions" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsPosition v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :positionToEdit="selectedPosition" @settingAdded="handleSettingAddition" :mode="mode"/>
            <DataTable :columns="columns" :data="positions" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

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

const selectedPosition = ref(null)

const mode = ref('add')

const handleEdit = async (position) => {
    selectedPosition.value = position
    mode.value = 'edit'
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const positions = ref([])

const isSettingsAddDialogOpen = ref(false)

const fetchPositions = async () => {
    const response = await api.get('/api/positions')
    positions.value = response.data.data
    console.log("Positions", positions.value)
}

const handleSettingAddition = (updatedPosition) => {
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