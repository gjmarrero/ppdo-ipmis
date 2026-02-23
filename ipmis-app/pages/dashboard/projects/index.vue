<template>
    <MetaTags title="Project Proposals" />
    <Toaster />
    <div v-if="loading" class="space-y-2 flex min-h-screen w-full flex-col bg-mainbg px-2 py-2">
        <TableSkeleton />
    </div>
    <div v-else class="flex min-h-screen w-full flex-col bg-mainbg px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="space-x-2">
                <Button @click="setFilter('all')"
                    :class="filterType === 'all' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">All</Button>
                <Button @click="setFilter('assigned')"
                    :class="filterType === 'assigned' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">Assigned</Button>
                <Button @click="setFilter('unassigned')"
                    :class="filterType === 'unassigned' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">Unassigned</Button>
                <Button @click="setFilter('validated')"
                    :class="filterType === 'validated' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">Validated</Button>
                <Button @click="setFilter('unvalidated')"
                    :class="filterType === 'unvalidated' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800'">Unvalidated</Button>
            </div>
            <Button variant="newprimary" @click="openAddProject" class="w-44">Add Project</Button>
        </div>



        <ProjectAdd v-if="isProjectDrawerOpen" v-model:isOpen="isProjectDrawerOpen" :mode="mode"
            :project="selectedProject" @form-submitted="handleFormSubmission(mode)"
            :key="mode + (selectedProject?.id || 'new')" />

        <DataTable :columns="columns" :data="projects" @open-detail="openDetail" @edit-project="editProject"
            @refresh="fetchProjects" />

        <ValidationValidate v-if="isValidationDrawerOpen" v-model="isValidationDrawerOpen" :project="selectedProject"
            :mode="mode" @submitted="fetchProjects" @form-submitted="handleValidationSubmission" />

        <ClientOnly>
            <ValidationDetails v-if="isDetailDrawerOpen" v-model:isOpen="isDetailDrawerOpen" :project="selectedProject"
                :key="selectedProject?.id" @assign="openValidationInput" @validate="openValidationInput"
                @edit-validation="openValidationEditInput"
                @manage-other-requirements="openManageOtherRequirementsDialog" />
        </ClientOnly>

        <EnvironmentalClearanceManageOtherRequirements v-if="showRequirementsDialog" v-model="showRequirementsDialog"
            :project="selectedProject" @submitted="handleOtherReqSubmission" @close="showRequirementsDialog = false" />
    </div>
</template>

<script setup>
import Toaster from '@/components/ui/toast/Toaster.vue'
import { useToast } from '@/components/ui/toast';
import { getProjectColumns } from './columns';

const { toast } = useToast()
const route = useRoute()
const router = useRouter()
const filterType = computed(() => route.query.filter ?? null)

const { projects, fetchProjects, applyFilter, loading } = useProjects(filterType)

const setFilter = async (filter) => {
    const newQuery = { ...route.query }

    if (filter === 'all') {
        delete newQuery.filter 
        delete newQuery.yearFunded   
    } else {
        newQuery.filter = filter     
    }

    router.push({ query: newQuery })
}

watch(
  () => route.query,
  async () => {
    await fetchProjects()
  },
  { immediate: true, deep: true }
)

const isProjectDrawerOpen = ref(false)
const isValidationDrawerOpen = ref(false)
const isDetailDrawerOpen = ref(false)

const mode = ref('add')
const selectedProject = ref(null)

const showRequirementsDialog = ref(false)

const columns = getProjectColumns({
    onEditProject: (project) => {
        mode.value = 'edit'
        selectedProject.value = project
        isProjectDrawerOpen.value = true
        console.log("Edit", selectedProject.value)
    },
    onOpenDetail: (project) => {
        selectedProject.value = project
        isDetailDrawerOpen.value = true
    },
    onRefresh: () => {
        fetchProjects()
    },
})

const openDetail = (project) => {
    selectedProject.value = project
    isDetailDrawerOpen.value = true
}

const openAddProject = () => {
    mode.value = 'add'
    selectedProject.value = null
    isProjectDrawerOpen.value = true
}

const editProject = (project) => {
    console.log("Edit project", project)
    mode.value = 'edit'
    selectedProject.value = project
    isProjectDrawerOpen.value = true
}

const handleFormSubmission = (mode) => {
    isProjectDrawerOpen.value = false
    toast({
        title: 'Success',
        description: `${mode === 'add' ? 'Added' : 'Updated'} Project`
    })
    fetchProjects()
}

const openValidationInput = () => {
    mode.value = 'add'
    isDetailDrawerOpen.value = false
    isValidationDrawerOpen.value = true
}

const openValidationEditInput = () => {
    mode.value = 'edit'
    isDetailDrawerOpen.value = false
    isValidationDrawerOpen.value = true
}

const handleValidationSubmission = () => {
    isValidationDrawerOpen.value = false
    toast({
        title: 'Success',
        description: 'Updated validation data'
    })
    fetchProjects()
}

const openManageOtherRequirementsDialog = async () => {
    isDetailDrawerOpen.value = false
    showRequirementsDialog.value = true
}

const handleOtherReqSubmission = async () => {
    fetchProjects()
}

onMounted(() => {
    fetchProjects()
    applyFilter()
    console.log("Projects", projects)
})

// const { api } = useAxios()
// const { toast } = useToast()

// const loading = ref(true)

// const data = ref([])
// const flattenedData = ref([])

// const isProjectDrawerOpen = ref(false)

// const isValidationDrawerOpen = ref(false)

// const isDetailDrawerOpen = ref(false)

// const mode = ref('add')

// const selectedProject = ref(null)

// function openAddProject() {
//     mode.value = 'add'
//     selectedProject.value = null
//     isProjectDrawerOpen.value = true
// }

// const openDetail = (project) => {
//     selectedProject.value = project
//     isDetailDrawerOpen.value = true
// }

// const editProject = (project) => {
//     console.log("Edit project", project)
//     mode.value = 'edit'
//     selectedProject.value = project
//     isProjectDrawerOpen.value = true
// }

// const loadProjects = async () => {
//     await fetchProjects()
// }

// const columns = getProjectColumns({
//     onEditProject: (project) => {
//         mode.value = 'edit'
//         selectedProject.value = project
//         isProjectDrawerOpen.value = true
//         console.log("Edit", selectedProject.value)
//     },
//     onOpenDetail: (project) => {
//         selectedProject.value = project
//         isDetailDrawerOpen.value = true
//     },
//     onRefresh: () => {
//         loadProjects()
//     },
// })

// const fetchProjects = async () => {
//     const response = await api.get('/api/projects')
//     data.value = response.data.data

//     flattenedData.value = data.value.map(item => {
//         const validation = item?.validation

//         const isValidated =
//             validation?.data !== null &&
//             validation?.data?.date_validated !== null

//         const isValidationAssigned =
//             validation?.data !== null &&
//             validation?.data?.date_assigned !== null

//         return {
//             ...item,
//             title: item?.title || '',
//             fundsource_id: item?.fundsource?.id || '',
//             fundsource: item?.fundsource?.fundsource || '',
//             project_type_id: item?.project_type?.id || '',
//             project_type: item?.project_type?.project_type || '',
//             cost: item?.cost || '',
//             project_status: item?.status || '',
//             project_type: item?.project_type?.project_type || '',
//             locations: item?.locations?.map(loc =>
//                 `${loc.sitio}, ${loc.barangay}, ${loc.municipality}`
//             ).join('; ') || 'No location',
//             validation_status: isValidated ? 'Validated' : 'Unvalidated',
//             validation_assignment: isValidationAssigned ? 'Assigned' : 'Unassigned',
//             locations_array: item?.locations
//         }
//     })

//     loading.value = false
// }

// function handleProjectAddition(project) {
//     toast({
//         title: 'Success',
//         description: 'Added new project proposal'
//     })
//     fetchProjects()
// }

// const handleFormSubmission = (mode) => {
//     isProjectDrawerOpen.value = false
//     toast({
//         title: 'Success',
//         description: `${mode === 'add' ? 'Added' : 'Updated'} Project`
//     })
//     fetchProjects()
// }

// const openValidationInput = () => {
//     isDetailDrawerOpen.value = false
//     isValidationDrawerOpen.value = true
// }

// const handleValidationSubmission = () => {
//     isValidationDrawerOpen.value = false
//     toast({
//         title: 'Success',
//         description: 'Updated validation data'
//     })
//     fetchProjects()
// }

// onMounted(() => {
//     fetchProjects()
// })

// onUnmounted(() => {
//     window.removeEventListener('open-project', () => { })
// })
</script>