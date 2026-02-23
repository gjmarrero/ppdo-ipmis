<template>
    <MetaTags title="Offices and Divisions" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsOdsu v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :odsuToEdit="selectedOdsu" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="odsus" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const tableKey = ref(0)

const settingsType = ref('odsu')

const isSettingsAddDialogOpen = ref(false)

const handleView = (odsu) => {
    console.log('View clicked: ', odsu)
}

const handleDelete = async (odsu) => {
    const response = await api.delete(`/api/odsu/${odsu.id}`)
    toast({
        description: response.data.message
    })
    fetchOdsus()
}

const selectedOdsu = ref(null)

const handleEdit = async (odsu) => {
    selectedOdsu.value = odsu
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const odsus = ref([])

const fetchOdsus = async () => {
    const response = await api.get('/api/odsus')
    odsus.value = response.data.data
}

const handleSettingAddition = (updatedOdsu) => {
    console.log("Added", updatedOdsu)
    const index = odsus.value.findIndex(o => o.id === updatedOdsu.id)
    if (index !== -1) {
        odsus.value.splice(index, 1, updatedOdsu)
        odsus.value = [...odsus.value]
        tableKey.value++
    } else {
        odsus.value = [...odsus.value, { ...updatedOdsu }]
    }
    selectedOdsu.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}

onMounted(() => {
    fetchOdsus()
})
</script>

<style lang="scss" scoped></style>