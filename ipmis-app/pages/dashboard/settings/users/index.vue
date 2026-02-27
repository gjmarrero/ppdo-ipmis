<template>
    <MetaTags title="Users" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly>
            <Toaster />
        </ClientOnly>
        <div class="flex min-h-screen w-full flex-col px-2 py-2">
            <SettingsUser v-model:isSettingsAddDialogOpen="isSettingsAddDialogOpen" :settingsType="settingsType"
                :userToEdit="selectedUser" @settingAdded="handleSettingAddition" :mode="mode" />
            <DataTable :columns="columns" :data="users" :key="tableKey" />
        </div>
    </div>

</template>

<script setup>
import { getColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const { api } = useAxios()

const tableKey = ref(0)

const dialogKey = ref(0)

const settingsType = ref('user')

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

const mode = ref('add')

const handleEdit = async (user) => {
    selectedUser.value = user
    console.log("Selected user", user)
    mode.value = 'edit'
    isSettingsAddDialogOpen.value = true
}

const columns = getColumns(handleView, handleDelete, handleEdit)

const users = ref([])

const isSettingsAddDialogOpen = ref(false)

const fetchUsers = async () => {
    const response = await api.get('/api/users')
    users.value = response.data.data
}

const handleSettingAddition = (updatedUser) => {
    console.log("User", updatedUser)
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

// watch(isSettingsAddDialogOpen, (val) => {
//   if (!val) {
//     dialogKey.value++
//   }
// })

onMounted(() => {
    fetchUsers()
})
</script>