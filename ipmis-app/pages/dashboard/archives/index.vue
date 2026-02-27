<template>
    <MetaTags title="Archives" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button variant="newprimary" class="w-42" @click="openAddArchive">Add Document</Button>
            </div>

            <ArchiveAdd v-if="isArchiveDrawerOpen" v-model:isOpen="isArchiveDrawerOpen"
                @archiveAdded="handleArchiveAddition" />
            <DataTable :columns="columns" :data="archives" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const { archives, fetchArchives } = useArchives()

const tableKey = ref(0)

const isArchiveDrawerOpen = ref(false)

const mode = ref('add')

const openAddArchive = () => {
    console.log("Open add drawer")
    mode.value = 'add'
    selectedArchive.value = null
    isArchiveDrawerOpen.value = true
}

const handleView = (archive) => {
    const fileUrl = archive.file
    window.open(fileUrl, '_blank')
}

const handleDelete = async (archive) => {
    const response = await api.delete(`/api/archive/${archive.id}`)
    toast({
        description: response.data.message
    })
    fetchArchives()
}

const selectedArchive = ref(null)

const handleEdit = async (archive) => {
    selectedArchive.value = archive
    isArchiveDrawerOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const handleArchiveAddition = () => {
    isArchiveDrawerOpen.value = false
    toast({
        title: 'Success',
        description: 'Added document'
    })
    fetchArchives()
    // console.log("Updated archive", updatedArchive)
    // const index = archives.value.findIndex(o => o.id === updatedArchive.id)
    // if (index !== -1) {
    //     archives.value.splice(index, 1, updatedArchive)
    //     archives.value = [...archives.value]
    //     tableKey.value++
    // } else {
    //     archives.value = [...archives.value, { ...updatedArchive }]
    // }
    // selectedArchive.value = null
    // toast({
    //     description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    // })
}

onMounted(() => {
    fetchArchives()
    
})
</script>