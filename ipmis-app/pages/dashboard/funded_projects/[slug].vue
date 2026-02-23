<template>
    <MetaTags title="Funded Project" />
    <div v-if="loading" class="bg-mainbg px-4 py-4">
        <ClientOnly>
            <SlugSkeleton />
        </ClientOnly>
    </div>
    <div v-else class="flex flex-col w-full px-4 py-4 bg-mainbg">
        <Toaster />
        <ValidationValidate v-if="isValidationFormDrawerOpen" v-model="isValidationFormDrawerOpen"
            :project="fundedProject?.project" :mode="validationMode"
            @openValidationFormDrawer="openValidationFormDrawer" @form-submitted="handleValidationSubmission" />
        <ClientOnly>
            <ValidationDetails v-if="isValidationDetailDrawerOpen" v-model:isOpen="isValidationDetailDrawerOpen"
                :project="fundedProject.project" @edit-validation="editValidation" />
        </ClientOnly>

        <PreEngineeringAdd v-if="isPreEngFormDrawerOpen" v-model="isPreEngFormDrawerOpen"
            :funded_project="fundedProject" :mode="preEngMode" @form-submitted="handlePreengineeringInput"
            :pre_engineering="fundedProject.preengineering" />
        <ClientOnly>
            <PreEngineeringDetails v-if="isPreEngDetailDrawerOpen" v-model:isOpen="isPreEngDetailDrawerOpen"
                :funded_project="fundedProject" @edit-preengineering="editPreEngineering" />
        </ClientOnly>

        <EnvironmentalClearanceAdd v-model="isEnviClearanceFormDrawerOpen" :funded_project="fundedProject" />

        <ImplementationDetails v-if="isImplementationDetailDrawerOpen" v-model:isOpen="isImplementationDetailDrawerOpen"
            :funded_project="fundedProject" />

        <div class="grid grid-cols-1 md:grid-cols-1 flex-grow overflow-y-auto">
            <ProjectCard :funded_project="fundedProject" />
        </div>

        <div class="w-full flex flex-row">
            <div class="w-full border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2 flex-grow items-stretch">
                    <ValidationCard v-if="fundedProject" :project="fundedProject?.project"
                        @openValidationFormDrawer="openValidationFormDrawer"
                        @openValidationDetailDrawer="openValidationDetailDrawer" class="h-full" />

                    <PreEngineeringCard v-if="fundedProject" :funded_project="fundedProject" :mode='preEngMode'
                        @openPreEngFormDrawer="openPreEngFormDrawer" @openPreEngDetailDrawer="openPreEngDetailDrawer"
                        class="h-full" />

                    <EnvironmentalClearanceCard v-if="fundedProject" :funded_project="fundedProject"
                        @openEnviClearanceFormDrawer="openEnviClearanceFormDrawer" class="h-full" />

                    <ProcurementCard :project_title="fundedProject?.project?.title" class="h-full" />

                    <ImplementationCard :implementation="fundedProject?.implementation" @openImplementationDetailDrawer="openImplementationDetailDrawer" class="h-full" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
definePageMeta({
    middleware: 'auth',
})

const { loading, fundedProject, fetchFundedProject } = useFundedProjects()
const { project, fetchProject } = useProjects()

import { toast } from '@/components/ui/toast'

import { Toaster } from '@/components/ui/toast'

const isValidationFormDrawerOpen = ref(false)
const isValidationDetailDrawerOpen = ref(false)
const validationMode = ref(null)

const openValidationFormDrawer = () => {
    validationMode.value = 'add'
    isValidationDetailDrawerOpen.value = false
    isValidationFormDrawerOpen.value = true
}

const editValidation = () => {
    validationMode.value = 'edit'
    isValidationDetailDrawerOpen.value = false
    isValidationFormDrawerOpen.value = true
}

const handleValidationSubmission = () => {
    isValidationFormDrawerOpen.value = false
    toast({
        title: 'Success',
        description: 'Updated validation data'
    })
    fetchFundedProject()
}

const openValidationDetailDrawer = () => {
    isValidationDetailDrawerOpen.value = true
}

const isPreEngDetailDrawerOpen = ref(false)
const isPreEngFormDrawerOpen = ref(false)
const preEngMode = ref(null)

const openPreEngFormDrawer = () => {
    preEngMode.value = 'add'
    isPreEngDetailDrawerOpen.value = false
    isPreEngFormDrawerOpen.value = true
}
const editPreEngineering = () => {
    preEngMode.value = 'edit'
    isPreEngDetailDrawerOpen.value = false
    isPreEngFormDrawerOpen.value = true
}

const handlePreengineeringInput = () => {
    isPreEngFormDrawerOpen.value = false
    toast({
        title: 'Success',
        description: 'Updated pre-engineering data',
        class: "bg-blue-500 text-white border-blue-600"
    })
    fetchFundedProject()
}

const openPreEngDetailDrawer = () => {
    isPreEngDetailDrawerOpen.value = true
}

const isEnviClearanceFormDrawerOpen = ref(false)
const openEnviClearanceFormDrawer = () => {
    isEnviClearanceFormDrawerOpen.value = true
}

const isImplementationDetailDrawerOpen = ref(false)
const openImplementationDetailDrawer = () => {
    isImplementationDetailDrawerOpen.value = true
}

// const openValidation = (project) => {
//     mode.value = 'add'
//     activeDrawer.value = 'validate'
// }

// const openEcc = (project) => {
//     activeDrawer.value = 'environmentalclearance'
// }

// const openValidationDetails = () => {
//     activeTopDrawer.value = 'validation'
// }

// const openPreEngineeringDetails = () => {
//     activeTopDrawer.value = 'preengineering'
// }

// const openActivityDialog = () => {
//     isActivityDialogOpen.value = true
// }

// const topDrawerKey = ref(0)


// const returnToPreEngineeringDetailsDrawer = async () => {
//     try {
//         const { data } = await api.get(`/api/funded_project/${route.params.slug}`)
//         funded_project.value = data.data
//         isActivityDialogOpen.value = false
//         topDrawerKey.value++

//     } catch (error) {
//         console.error('Failed to reload funded project:', error)
//     }
// }

// const image_urls = ref([])

// const file_urls = ref([])

// const editValidation = () => {
//     mode.value = 'edit'
//     activeDrawer.value = 'validate'
// }

// const handleValidationInput = () => {
//     toast({
//         title: 'Success',
//         description: 'Validation info added'
//     })
//     activeDrawer.value = null
//     getFundedProject()
// }

// const handlePreengineeringInput = () => {
//     isPreEngFormDrawerOpen.value = false
//     toast({
//         title: 'Success',
//         description: 'Pre-engineering info added'
//     })
//     fetchFundedProject()
// }

// const handleEnvironmentalClearanceInput = () => {
//     activeDrawer.value = null
//     toast({
//         title: 'Success',
//         description: 'Environmental clearance info added'
//     })
//     getFundedProject()
// }



onMounted(() => {
    fetchFundedProject()
    watchEffect( ()=> {
        if(fundedProject.value) {
            fetchProject(fundedProject.value.project?.id)
            watchEffect( () => {
                console.log("Main project loaded", project)
            })
        }
    })
    
    
})

</script>