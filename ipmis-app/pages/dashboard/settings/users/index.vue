<template>
    <MetaTags title="Users" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <div class="flex flex-row justify-end">
                <Button @click="openDialog" variant="newprimary" class="w-40">Add</Button>
            </div>
            <SettingsUser v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :userToEdit="selectedUser" @settingAdded="handleSettingAddition" />
            <DataTable :columns="columns" :data="users" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()
const { users, fetchUsers } = useUsers()

const tableKey = ref(0)

const handleView = (user) => {
    console.log('View clicked: ', user)
}

const handleDelete = async (user) => {
    const response = await api.delete(`/api/user/${user.id}`)
    toast({
        description: response.data.message
    })
    fetchUsers()
}

const selectedUser = ref(null)

const isSettingsAddDialogOpen = ref(false)

const handleEdit = async (user) => {
    selectedUser.value = user
    isSettingsAddDialogOpen.value = true
}

const openDialog = () => {
    selectedUser.value = null
    isSettingsAddDialogOpen.value = true
}
const columns = getColumns(handleView, handleDelete, handleEdit)

const handleSettingAddition = (response) => {
    if(!response) return

    const updatedUser = response.data.data
    const index = users.value.findIndex(o => o.id === updatedUser.id)
    if (index !== -1) {
        users.value.splice(index, 1, updatedUser)
        users.value = [...users.value]
        tableKey.value++
    } else {
        users.value = [...users.value, { ...updatedUser }]
    }
    selectedUser.value = null
    toast({
        description: index !== -1 ? 'Successfully updated' : 'Successfully added'
    })
}


onMounted(() => {
    fetchUsers()
})
</script>