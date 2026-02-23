<template>
    <MetaTags title="Project Beneficiary Pivot" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly><Toaster /></ClientOnly>
        <ProjectBeneficiaryPivotAdd @add="handleBeneficiaryAddition"/>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 shadow">
            <ProjectBeneficiaryPivotCard v-for="projecttype in projecttypes" :key="projecttype.id" :projecttype="projecttype"/>            
        </div>
    </div>
</template>

<script setup>

import { useToast } from '@/components/ui/toast'
import Toaster from '@/components/ui/toast/Toaster.vue'

const {api} = useAxios()
const {toast} = useToast()
const project_beneficiaries = ref([])

function loadProjectBeneficiaries(){
    api.get('/api/project_beneficiaries').then(({data}) => {
        project_beneficiaries.value = data.data
    })
}

const projecttypes = ref([])

const fetchProjectTypes = () => {
    api.get('/api/fetchProjectTypes').then(({ data }) => {
        projecttypes.value = data
    })
}

const handleBeneficiaryAddition = () => {
    toast({
        title: 'Success',
        description: 'Beneficiary type added'
    })
    loadProjectBeneficiaries()
    fetchProjectTypes()
}
onMounted(() => {
    loadProjectBeneficiaries()
    fetchProjectTypes()
})
</script>