<template>
  <MetaTags title="Funded Projects" />
  <Toaster />
  <div v-if="loading" class="space-y-2 flex min-h-screen w-full flex-col bg-mainbg px-2 py-2">
    <TableSkeleton />
  </div>
  <div v-else class="flex min-h-screen w-full flex-col bg-mainbg px-4 py-4">
    <div class="flex justify-end">
      <Button variant="newprimary" @click="openAddFundedProject" class="w-44 items-end">Add Funded Project</Button>
    </div>

    <FundedProjectAdd v-if="isFundedProjectDrawerOpen" v-model:isOpen="isFundedProjectDrawerOpen" :mode="mode"
      :fundedProject="selectedProject" @form-submitted="handleFormSubmission(mode)"
      :key="mode + (selectedProject?.id || 'new')" />


    <DataTable :columns="columns" :data="fundedProjects" @open-detail="openDetail" />

    <PreEngineeringSiteProblem v-if="isSiteProblemDrawerOpen" v-model="isSiteProblemDrawerOpen"
      :funded_project="selectedProject" @form-submitted="handleSiteProblemSubmission" />

    <FundedProjectSiteProblemPdcResult v-if="isPdcResultDrawerOpen" v-model="isPdcResultDrawerOpen" :funded_project="selectedProject" @form-submitted="handlePdcResultSubmission"/>

    <!-- <ClientOnly>
      <Details v-if="isDetailDrawerOpen" v-model:isOpen="isDetailDrawerOpen" :funded_project="selectedProject"
        :key="selectedProject?.id" />
    </ClientOnly> -->
  </div>
</template>

<script setup>
import { toast, Toaster } from '@/components/ui/toast';
import TableSkeleton from '@/components/TableSkeleton.vue';
import { getProjectColumns } from './columns';

const { loading, fundedProjects, applyFilter, fetchFundedProjects, resetForm, error } = useFundedProjects()

const isFundedProjectDrawerOpen = ref(false)

const mode = ref('add')

const selectedProject = ref(null)

const isSiteProblemDrawerOpen = ref(false)

const isPdcResultDrawerOpen = ref(false)

const columns = getProjectColumns({
  onEditProject: (project) => {
    mode.value = 'edit'
    selectedProject.value = project
    isFundedProjectDrawerOpen.value = true
    console.log("Edit", selectedProject.value)
  },
  onOpenDetail: (project) => {
    selectedProject.value = project
    isDetailDrawerOpen.value = true
  },
  onSiteProblem: (project) => {
    selectedProject.value = project
    isSiteProblemDrawerOpen.value = true
  },
  onPdcResult: (project) => {
    selectedProject.value = project
    isPdcResultDrawerOpen.value = true
  },
  onRefresh: () => {
    loadProjects()
  },
})

const isDetailDrawerOpen = ref(false)

const openDetail = (project) => {
  console.log("View funded project")
  selectedProject.value = project
  isDetailDrawerOpen.value = true
}

const openAddFundedProject = () => {
  mode.value = 'add'
  selectedProject.value = null
  isFundedProjectDrawerOpen.value = true
}

const siteProblem = () => {
    isSiteProblemDrawerOpen.value = true
}

const handleFormSubmission = (mode) => {
  isFundedProjectDrawerOpen.value = false
  toast({
    title: 'Success',
    description: `${mode === 'add' ? 'Added' : 'Updated'} Funded Project`
  })
  fetchFundedProjects()
}

const handlePdcResultSubmission = () => {
  console.log("submitted pdc result")
  isPdcResultDrawerOpen.value = false
  toast({
    title: 'Success',
    description: 'Added PDC result'
  })
  fetchFundedProjects()
}

const handleSiteProblemSubmission = () => {
  isSiteProblemDrawerOpen.value = false
  toast({
    title: 'Success',
    description: 'Added site problem'
  })
  fetchFundedProjects()
}
onMounted(() => {
  fetchFundedProjects()
  applyFilter()
  console.log("Funded projects", fundedProjects)
})

</script>