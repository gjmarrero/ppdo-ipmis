<template>
    <MetaTags title="Scopes of Work" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
            </div>
            <SettingsScopeOfWork v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :scopeOfWorkToEdit="selectedScopeOfWork" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="scopes_of_work" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns'
import { toast, Toaster } from '@/components/ui/toast'

const { api } = useAxios()
const { scopes_of_work, fetchScopesOfWork } = useSpecificScopeOfWorks()

const tableKey = ref(0)

const handleView = (scopeOfWork) => {
    console.log('View clicked: ', scopeOfWork)
}

const handleDelete = async (scopeOfWork) => {
    const response = await api.delete(`/api/scope_of_work/${scopeOfWork.id}`)
    toast({
        description: response.data.message
    })
    fetchScopesOfWork()
}

const selectedScopeOfWork = ref(null)

const handleEdit = async (scopeOfWork) => {
    selectedScopeOfWork.value = scopeOfWork
    isSettingsAddDialogOpen.value = true
}

const openDialog = () => {
    selectedScopeOfWork.value = null
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const isSettingsAddDialogOpen = ref(false)

const handleSettingAddition = (response) => {
    if(!response) return

    const updatedScopeOfWork = response.data.data
    const index = scopes_of_work.value.findIndex(o => o.id === updatedScopeOfWork.id)
    if (index !== -1) {
        scopes_of_work.value.splice(index, 1, updatedScopeOfWork)
        scopes_of_work.value = [...scopes_of_work.value]
        tableKey.value++
    } else {
        scopes_of_work.value = [...scopes_of_work.value, { ...updatedScopeOfWork }]
    }
    selectedScopeOfWork.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}

onMounted(() => {
    fetchScopesOfWork()
})
</script>