<template>
    <MetaTags title="Project Types" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
            </div>
            <SettingsProjectType v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :projectTypeToEdit="selectedProjectType" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="projectTypes" :key="tableKey" />
        </div>
    </div>
</template>

<script setup>
import { getColumns } from './columns';

const { projectTypes, fetchProjectTypes } = useProjectTypes()

import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const tableKey = ref(0)

const isSettingsAddDialogOpen = ref(false)

const handleView = (project_type) => {
    console.log('View clicked: ', project_type)
}

const handleDelete = async (project_type) => {
    const response = await api.delete(`/api/project_type/${project_type.id}`)
    toast({
        description: response.data.message
    })
    fetchProjectTypes()
}

const openDialog = () => {
    selectedProjectType.value = null
    isSettingsAddDialogOpen.value = true
}

const selectedProjectType = ref(null)

const handleEdit = async (project_type) => {
    selectedProjectType.value = project_type
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const handleSettingAddition = (updatedProjectType) => {
    const index = projectTypes.value.findIndex(o => o.id === updatedProjectType.id)
    if (index !== -1) {
        projectTypes.value.splice(index, 1, updatedProjectType)
        projectTypes.value = [...projectTypes.value]
        tableKey.value++
    } else {
        projectTypes.value = [...projectTypes.value, { ...updatedProjectType }]
    }
    selectedProjectType.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
    fetchProjectTypes()
}

onMounted(() => {
    fetchProjectTypes()
})
</script>


<style lang="scss" scoped></style>