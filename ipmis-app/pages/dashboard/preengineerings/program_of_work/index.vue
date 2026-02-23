<template>
    <MetaTags title="Pre-engineering Status" />
    <Toaster />
    <div v-if="loading" class="space-y-2 flex min-h-screen w-full flex-col bg-mainbg px-2 py-2">
        <TableSkeleton />
    </div>
    <div v-else class="flex min-h-screen w-full flex-col bg-mainbg px-4 py-4">
        <div class="space-x-2">
            <Button @click="setFilter('all')"
                :class="filterType === 'all' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">All</Button>
            <Button @click="setFilter('preengineered')"
                :class="filterType === 'preengineered' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">With
                Pre-engineering Data</Button>
            <Button @click="setFilter('unpreengineered')"
                :class="filterType === 'unpreengineered' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">No
                Pre-engineering Data</Button>
        </div>

        <PreEngineeringAdd v-if="isPreEngFormDrawerOpen" v-model="isPreEngFormDrawerOpen" :mode="mode"
            :funded_project="selectedProject" :pre_engineering="selectedProject.preengineering"
            @form-submitted="handleFormSubmission(mode)" :key="mode + (selectedProject?.id || 'new')" />

        <DataTable :columns="columns" :data="preEngineerings" @open-detail="openDetail" @site-problem="siteProblem" />

        <ClientOnly>
            <PreEngineeringDetails v-if="isDetailDrawerOpen" v-model:isOpen="isDetailDrawerOpen"
                :funded_project="selectedProject" :key="selectedProject?.id" @edit-preengineering="openEditPreEng"
                @add-preengineering="openAddPreEng" @open-activity-dialog="openAddActivityDialog"
                @manage-other-requirements="openManageOtherRequirements" @revise-pow="openAddPreEng" :showPow="true" :showQc="false" :showReview="false"/>
        </ClientOnly>

        <PreEngineeringActivity v-if="isActivityDialogOpen" v-model="isActivityDialogOpen"
            :pre_engineering="selectedProject.preengineering" @submitted="closeActivityDialog" />

        <PreEngineeringSiteProblem v-if="isSiteProblemDrawerOpen" v-model="isSiteProblemDrawerOpen"
            :funded_project="selectedProject" @form-submitted="handleSiteProblemSubmission" />

        <EnvironmentalClearanceManageOtherRequirements v-if="showRequirementsDialog" v-model="showRequirementsDialog"
            :project="selectedProject" @submitted="handleOtherReqSubmission" @close="showRequirementsDialog = false" />
    </div>
</template>

<script setup>
import { toast, Toaster } from '@/components/ui/toast';
import { getPreEngColumns } from './columns';
import TableSkeleton from '@/components/TableSkeleton.vue';

const route = useRoute()
const router = useRouter()

const filterType = computed(() => route.query.filter ?? 'all')

const { loading, preEngineerings, fetchPreEngineerings, applyFilter } = usePreEngineerings(filterType)

const setFilter = (filter) => {
    router.push({
        query: {
            ...route.query,
            filter: filter === 'all' ? undefined : filter
        }
    })
}

const isPreEngFormDrawerOpen = ref(false)
const isDetailDrawerOpen = ref(false)
const isActivityDialogOpen = ref(false)
const isSiteProblemDrawerOpen = ref(false)
const showRequirementsDialog = ref(false)

const mode = ref('add')

const selectedProject = ref(null)

const columns = getPreEngColumns({
    onEditPreEng: (preEngineering) => {
        mode.value = 'edit'
        selectedProject.value = preEngineering
        isPreEngFormDrawerOpen.value = true
        console.log("Edit", selectedProject.value)
    },
    onOpenDetail: (project) => {
        selectedProject.value = project
        isDetailDrawerOpen.value = true
        console.log("Selected preeng", selectedProject.value)
    },
    onSiteProblem: (project) => {
        selectedProject.value = project
        isSiteProblemDrawerOpen.value = true
        console.log("With site problem", project)
    },
    onRefresh: () => {
        loadProjects()
    },
})

const openDetail = (project) => {
    selectedProject.value = project
    isDetailDrawerOpen.value = true
}

const openAddPreEng = () => {
    mode.value = 'add'
    isDetailDrawerOpen.value = false
    isPreEngFormDrawerOpen.value = true
}

const openEditPreEng = () => {
    mode.value = 'edit'
    isDetailDrawerOpen.value = false
    isPreEngFormDrawerOpen.value = true
}

const refreshDetailDrawer = async () => {
    await fetchPreEngineerings()

    const updated = preEngineerings.value.find(
        p => p.id === selectedProject.value.id
    )

    if (updated) {
        selectedProject.value = { ...updated}
        isDetailDrawerOpen.value = true
    }
}

const siteProblem = () => {
    isSiteProblemDrawerOpen.value = true
}

const handleFormSubmission = async (mode) => {
    isPreEngFormDrawerOpen.value = false
    toast({
        title: 'Success',
        description: `${mode === 'add' ? 'Added' : 'Updated'} Pre-engineering status`
    })
    refreshDetailDrawer()
}

const handleSiteProblemSubmission = () => {
    isSiteProblemDrawerOpen.value = false
    toast({
        title: 'Success',
        description: 'Added site problem'
    })
    refreshDetailDrawer()
}

const openAddActivityDialog = () => {
    isDetailDrawerOpen.value = false
    isActivityDialogOpen.value = true
}

const closeActivityDialog = () => {
    isActivityDialogOpen.value = false
    isDetailDrawerOpen.value = true
}

const openManageOtherRequirements = () => {
    showRequirementsDialog.value = true
}

const handleOtherReqSubmission = async () => {
    toast({
        title: 'Success',
        description: 'Updated Other Requirement/s'
    })
    refreshDetailDrawer()
}

onMounted(() => {
    fetchPreEngineerings()
    applyFilter()
})

</script>