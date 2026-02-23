<template>
    <MetaTags title="POWs for Approval" />
    <Toaster />
    <div v-if="loading" class="space-y-2 flex min-h-screen w-full flex-col bg-mainbg px-2 py-2">
        <TableSkeleton />
    </div>
    <div v-else class="flex min-h-screen w-full flex-col bg-mainbg px-4 py-4">
        <!-- <div class="space-x-2">
            <Button @click="setFilter('all')"
                :class="filterType === 'all' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">All</Button>
            <Button @click="setFilter('preengineered')"
                :class="filterType === 'preengineered' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">With
                Pre-engineering Data</Button>
            <Button @click="setFilter('unpreengineered')"
                :class="filterType === 'unpreengineered' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">No
                Pre-engineering Data</Button>
        </div> -->

        <DataTable :columns="columns" :data="forApprovalPows" @open-detail="openDetail" />

        <PowForApprovalAdd v-if="isPowForApprovalFormDrawerOpen" v-model="isPowForApprovalFormDrawerOpen"
            :pre_engineering="selectedProject" @form-submitted="handleApprovedPowSubmission" />

        <!-- <PreEngineeringAdd v-if="isPreEngFormDrawerOpen" v-model="isPreEngFormDrawerOpen" :mode="mode"
            :funded_project="selectedProject" :pre_engineering="selectedProject.preengineering"
            @form-submitted="handleFormSubmission(mode)" :key="mode + (selectedProject?.id || 'new')" /> -->

        <!-- <ClientOnly>
            <PreEngineeringDetails v-if="isDetailDrawerOpen" v-model:isOpen="isDetailDrawerOpen"
                :funded_project="selectedProject" :key="selectedProject?.id" @edit-preengineering="openEditPreEng"
                @add-preengineering="openAddPreEng" @open-activity-dialog="openAddActivityDialog"/>
        </ClientOnly>

        <PreEngineeringActivity v-if="isActivityDialogOpen" v-model="isActivityDialogOpen" :pre_engineering="selectedProject.preengineering" @submitted="closeActivityDialog"/> -->

    </div>
</template>

<script setup>
import { toast, Toaster } from '@/components/ui/toast';
import { getForApprovalPowsColumns } from './columns';
import TableSkeleton from '@/components/TableSkeleton.vue';

const filterType = ref('all')

const { loading, forApprovalPows, fetchForApprovalPows, applyFilter } = usePowForApproval(filterType)

const setFilter = (type) => {
    filterType.value = type
    applyFilter()
}

const isPowForApprovalFormDrawerOpen = ref(false)

const mode = ref('add')

const selectedProject = ref(null)

const columns = getForApprovalPowsColumns({
    onApprovePow: (powForApproval) => {
        selectedProject.value = powForApproval.latest_funding.latest_preengineering
        isPowForApprovalFormDrawerOpen.value = true
    }
})

const handleApprovedPowSubmission = (mode) => {
    isPowForApprovalFormDrawerOpen.value = false
    toast({
        title: 'Success',
        description: 'Approved POW'
    })
    fetchForApprovalPows()
}

onMounted(() => {
    fetchForApprovalPows()
    applyFilter()
})

</script>