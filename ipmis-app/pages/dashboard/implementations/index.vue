<template>
    <MetaTags title="Implementation Schedule" />
    <Toaster />
    <div v-if="loading" class="space-y-2 flex min-h-screen w-full flex-col bg-muted/40 px-2 py-2">
        <TableSkeleton />
    </div>
    <div v-else class="flex min-h-screen w-full flex-col bg-muted/40 px-4 py-4">
        <div class="space-x-2">
            <Button @click="setFilter('all')"
                :class="filterType === 'all' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">All</Button>
            <Button @click="setFilter('received')"
                :class="filterType === 'received' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">Documents
                Received</Button>
            <Button @click="setFilter('pending')"
                :class="filterType === 'pending' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">No Documents
                Received</Button>
        </div>

        <ImplementationAdd v-if="isImplementationFormDrawerOpen" v-model="isImplementationFormDrawerOpen"
            :funded_project="selectedProject" :mode="mode" :implementation="selectedProject?.implementation"
            @form-submitted="handleFormSubmission(mode)" />

        <ImplementationDetails v-if="isDetailDrawerOpen" v-model:isOpen="isDetailDrawerOpen"
            :funded_project="selectedProject" @add-implementation="openAddImplementation"
            @edit-implementation="openEditImplementation" @add-schedule="openAddSchedule"
            @edit-schedule="openEditSchedule" @add-accomplishment="openAddAccomplishment" @add-order="openAddOrder" />

        <ImplementationAddSchedule v-if="isScheduleFormDialogOpen" v-model:isOpen="isScheduleFormDialogOpen"
            @form-submitted="handleScheduleFormSubmission"
            :implementation="selectedProject?.latest_funding?.latest_implementation" :duration="project_duration"
            :mode="scheduleMode" :implementationId="implementationId" />

        <ImplementationAddAccomplishment v-if="isAccomplishmentFormDialogOpen"
            v-model:isOpen="isAccomplishmentFormDialogOpen"
            :implementation="selectedProject?.latest_funding?.latest_implementation" @form-submitted="handleAccomplishmentFormSubmission"/>

        <ImplementationAddOrder v-if="isStatusFormDialogOpen" v-model:isOpen="isStatusFormDialogOpen"
            :implementation="selectedProject?.latest_funding?.latest_implementation"
            @order-submitted="handleOrderSubmission" />
        <div class="table-wrapper overflow-x-auto w-full">
            <DataTable :columns="columns" :data="implementations" />
        </div>
        
    </div>
</template>

<script setup>
import TableSkeleton from '@/components/TableSkeleton.vue';
import { getImplementationColumns } from './columns';
import { toast, Toaster } from '@/components/ui/toast';

const filterType = ref('all')
const { loading, implementations, fetchImplementations, applyFilter } = useImplementations(filterType)
const mode = ref('add')
const isImplementationFormDrawerOpen = ref(false)
const isAccomplishmentFormDialogOpen = ref(false)
const isScheduleFormDialogOpen = ref(false)
const isStatusFormDialogOpen = ref(false)

const setFilter = (type) => {
    filterType.value = type
    applyFilter()
}

const isDetailDrawerOpen = ref(false)
const selectedProject = ref(null)

const scheduleMode = ref('add')
const implementationId = ref(null)

const columns = getImplementationColumns({
    onEditPreEng: (preEngineering) => {
        mode.value = 'edit'
        selectedProject.value = preEngineering
        isPreEngineeringFormDrawerOpen.value = true
        console.log("Edit", selectedProject.value)
    },
    onOpenDetail: (project) => {
        selectedProject.value = project
        isDetailDrawerOpen.value = true
    },
    onRefresh: () => {
        loadProjects()
    },
})

const openAddImplementation = () => {
    mode.value = 'add'
    isDetailDrawerOpen.value = false
    isImplementationFormDrawerOpen.value = true
}

const openEditImplementation = () => {
    mode.value = 'edit'
    isDetailDrawerOpen.value = false
    isImplementationFormDrawerOpen.value = true
}

const handleFormSubmission = (mode) => {
    isImplementationFormDrawerOpen.value = false
    toast({
        title: 'Success',
        description: `${mode === 'add' ? 'Added' : 'Updated'} Implementation Schedule`
    })
    fetchImplementations()
}

const openAddAccomplishment = () => {
    isDetailDrawerOpen.value = false
    isAccomplishmentFormDialogOpen.value = true
}

const project_duration = ref(null)

const openAddSchedule = (duration) => {
    isDetailDrawerOpen.value = false
    isScheduleFormDialogOpen.value = true
    project_duration.value = Number(duration)
    console.log("Adding", project_duration.value)
}

const openEditSchedule = (duration, implementation_id) => {
    scheduleMode.value = 'edit'
    implementationId.value = implementation_id
    project_duration.value = Number(duration)
    isDetailDrawerOpen.value = false
    isScheduleFormDialogOpen.value = true
}

const openAddOrder = () => {
    console.log("current project", selectedProject.value)
    // isDetailDrawerOpen.value = false
    isStatusFormDialogOpen.value = true
}

const handleOrderSubmission = () => {
    isStatusFormDialogOpen.value = false
    toast({
        title: 'Success',
        description: 'Order saved'
    })
}

const handleScheduleFormSubmission = () => {
    isScheduleFormDialogOpen.value = false
    toast({
        title: 'Success',
        description: 'Schedule saved'
    })
}

const handleAccomplishmentFormSubmission = () => {
    isAccomplishmentFormDialogOpen.value = false
    toast({
        title: 'Success',
        description: 'Accomplishment saved'
    })
}
onMounted(() => {
    fetchImplementations()


})

</script>

<style lang="scss" scoped>
.table-wrapper {
    transform-origin: top left;
}

@media (max-width: 1024px) {
    .table-wrapper {
        transform: scale(0.9);
    }
}

@media (max-width: 768px) {
    .table-wrapper {
        transform: scale(0.8);
    }
}

@media (max-width: 480px) {
    .table-wrapper {
        transform: scale(0.7);
    }
}
</style>