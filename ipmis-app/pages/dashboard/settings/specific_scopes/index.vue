<template>
    <MetaTags title="Scopes of Work" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsScopeOfWork v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :scopeOfWorkToEdit="selectedScopeOfWork" @settingAdded="handleSettingAddition" :mode="mode"/>
            <DataTable :columns="columns" :data="scopes_of_work" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const tableKey = ref(0)

const settingsType = ref('scopeOfWork')

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

const mode = ref('add')

const handleEdit = async (scopeOfWork) => {
    selectedScopeOfWork.value = scopeOfWork
    mode.value = 'edit'
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const scopes_of_work = ref([])

const isSettingsAddDialogOpen = ref(false)

const fetchScopesOfWork = async () => {
    const response = await api.get('/api/scopes_of_work')
    scopes_of_work.value = response.data.data
}

const handleSettingAddition = (updatedScopeOfWork) => {
    console.log("Updated scope of work", updatedScopeOfWork)
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