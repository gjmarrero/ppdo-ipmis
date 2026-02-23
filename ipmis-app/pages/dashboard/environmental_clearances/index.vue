<template>
    <MetaTags title="Environmental Clearances" />
    <Toaster />
    <div v-if="loading" class="space-y-2 flex min-h-screen w-full flex-col bg-mainbg px-2 py-2">
        <TableSkeleton />
    </div>
    <div v-else class="flex min-h-screen w-full flex-col bg-mainbg px-4 py-4">
        <div class="space-x-2">
            <Button @click="setFilter('all')"
                :class="filterType === 'all' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">All</Button>
            <Button @click="setFilter('clearance_issued')"
                :class="filterType === 'clearance_issued' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">Clearance
                Issued</Button>
            <Button @click="setFilter('on_process')"
                :class="filterType === 'on_process' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">On Process</Button>
            <Button @click="setFilter('for_processing')"
                :class="filterType === 'for_processing' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">For Application</Button>
        </div>

        <EnvironmentalClearanceDetails v-if="isDetailDrawerOpen" v-model:isOpen="isDetailDrawerOpen"
            :funded_project="selectedProject" @add-environmental-clearance="openAddEnviClearance"
            @edit-environmental-clearance="openEditEnviClearance" @manage-other-requirements="openManageOtherRequirementsDialog" @update-cmr="openUpdateCmr"/>

        <EnvironmentalClearanceAdd v-if="isEnviClearanceFormDrawerOpen" v-model="isEnviClearanceFormDrawerOpen"
            :mode="mode" :funded_project="selectedProject"
            :environmental_clearance="selectedProject?.latest_funding?.latest_environmental_clearance"
            @form-submitted="handleFormSubmission(mode)" :key="mode + (selectedProject?.id || 'new')" />

        <EnvironmentalClearanceCmr v-if="isCmrDialogOpen" v-model="isCmrDialogOpen" :funded_project="selectedProject" @form-submitted="handleCmrFormSubmission"/>
        
        <EnvironmentalClearanceManageOtherRequirements v-if="showRequirementsDialog" v-model="showRequirementsDialog" :project="selectedProject" @submitted="handleOtherReqSubmission" @close="showRequirementsDialog = false" />

        <DataTable :columns="columns" :data="environmentalClearances" @open-detail="openDetail" />

    </div>
</template>

<script setup>
import { toast, Toaster } from '@/components/ui/toast';
import { getEnviClearanceColumns } from './columns';
import TableSkeleton from '@/components/TableSkeleton.vue';

const route = useRoute()
const filterType = ref('all')
const { loading, environmentalClearances, fetchEnvironmentalClearances, applyFilter, errors } = useEnvironmentalClearances(filterType)

const isDetailDrawerOpen = ref(false)
const selectedProject = ref(null)

const isEnviClearanceFormDrawerOpen = ref(false)
const mode = ref('add')

const showRequirementsDialog = ref(false)

const isCmrDialogOpen = ref(false)

const setFilter = (type) => {
    filterType.value = type
    applyFilter()
}

const columns = getEnviClearanceColumns({
    onEditPreEng: (preEngineering) => {
        mode.value = 'edit'
        selectedProject.value = preEngineering
        isPreEngineeringFormDrawerOpen.value = true
        console.log("Edit", selectedProject.value)
    },
    onOpenDetail: (project) => {
        selectedProject.value = project
        isDetailDrawerOpen.value = true
        console.log("Open details")
    },
    onRefresh: () => {
        loadProjects()
    },
})

const openDetail = (project) => {
    selectedProject.value = project
    isDetailDrawerOpen.value = true
}

const openAddEnviClearance = async () => {
    mode.value = 'add'
    isDetailDrawerOpen.value = false
    isEnviClearanceFormDrawerOpen.value = true
}

const openEditEnviClearance = async () => {
    mode.value = 'edit'
    isDetailDrawerOpen.value = false
    isEnviClearanceFormDrawerOpen.value = true
}

const openManageOtherRequirementsDialog = async () => {
    isDetailDrawerOpen.value = false
    showRequirementsDialog.value = true
}

const refreshDetailDrawer = async () => {
    await fetchEnvironmentalClearances()

    const updated = environmentalClearances.value.find(
        p => p.id === selectedProject.value.id
    )

    if (updated) {
        selectedProject.value = { ...updated}
        isDetailDrawerOpen.value = true
    }
}

const handleFormSubmission = (mode) => {
    isEnviClearanceFormDrawerOpen.value = false
    toast({
        title: 'Success',
        description: `${mode === 'add' ? 'Added' : 'Updated'} environmental clearance data`
    })
    refreshDetailDrawer()
}

const handleOtherReqSubmission = () => {
    toast({
        title: 'Success',
        description: 'Updated Other Requirement/s'
    })
    refreshDetailDrawer()
}

const handleCmrFormSubmission = () => {
    isCmrDialogOpen.value = false
    toast({
        title:'Success',
        description: 'Updated CMR'
    })
    refreshDetailDrawer()
}

const openUpdateCmr = () => {
    isCmrDialogOpen.value = true
}
onMounted(() => {
    filterType.value = route.query.filter || ''
    fetchEnvironmentalClearances()
    applyFilter()
    console.log("Projects for ecccnc", environmentalClearances)
})
</script>

<style lang="scss" scoped></style>