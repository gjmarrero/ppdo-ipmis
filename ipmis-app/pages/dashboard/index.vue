<template>
    <MetaTags title="Dashboard" description="Project statistics" />
    <div class="py-4 px-2 bg-mainbg text-textprimary">
        <div class="flex flex-row w-full px-6">
            <form @submit.prevent="handleFilterFormSubmit" class="flex items-end gap-4 w-1/4">
                <FormGroup label="Year Funded" name="yearFunded" required labelFor="yearFunded" class="flex-1">
                    <FormCombobox v-model="filterForm.yearFunded" id="yearFunded" :options="years"
                        placeholder="Select Year" />
                </FormGroup>
                <div class="pb-1">
                    <Button variant="newprimary">Filter</Button>
                </div>
            </form>

        </div>
        <div v-if="user.role === '1' || user.role === 'ppdoDevStaff' || user.role === 'ppdoDevChief'">
            <Heading as="h4">Project Proposals (PPDO-Project Dev Division)</Heading>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 m-3">
                <NuxtLink :to="buildRoute('/dashboard/projects', 'all')">
                    <DashboardStatCard title="Project Proposals" subtitle="Total project proposals"
                        :count="counts.project_proposals" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/projects', 'validated')">
                    <DashboardStatCard title="Validated Proposals" subtitle="Total validated proposals"
                        :count="counts.validated_proposals" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/projects', 'unvalidated')">
                    <DashboardStatCard title="Unvalidated Proposals" subtitle="Total unvalidated proposals"
                        :count="counts.unvalidated_proposals" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/projects', 'funded_proposals')">
                    <DashboardStatCard title="Funded Project Proposals" subtitle="Total funded project proposals"
                        :count="counts.funded_proposals" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/projects', 'validation_assignments')">
                    <DashboardStatCard title="Assigned" subtitle="Assigned pending validation"
                        :count="counts.validation_my_assignments" />
                </NuxtLink>
            </div>
        </div>

        <div v-if="user.role === '1' || user.role === 'ppdoMonitoringStaff' || user.role === 'ppdoMonitoringChief'">
            <Heading as="h4">Funded Projects</Heading>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 m-3">
                <NuxtLink :to="buildRoute('/dashboard/funded_projects', 'all')">
                    <DashboardStatCard title="Funded Projects" subtitle="Total funded projects"
                        :count="counts.funded_projects" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/funded_projects', 'validated')">
                    <DashboardStatCard title="Validated Funded Projects" subtitle="Total validated funded projects"
                        :count="counts.validated_funded_projects" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/funded_projects', 'programmed')">
                    <DashboardStatCard title="Funded Projects with Preengineering"
                        subtitle="Total funded projects with Preengineering" :count="counts.preengineered_projects" />
                </NuxtLink>


                <DashboardStatCard title="Funded Projects with Procurement"
                    subtitle="Total funded projects with Procurement" :count="counts.preengineered_projects"
                    :onClick="() => navigateToFilteredProjects('all')" />
                <DashboardStatCard title="Funded Projects Implemented" subtitle="Total funded projects Implemented"
                    :count="counts.preengineered_projects" :onClick="() => navigateToFilteredProjects('all')" />
            </div>
        </div>

        <div v-if="user.role === '1' || user.role === 'peoPlanningStaff' || user.role === 'peoPlanningChief'">
            <Heading as="h4">Pre-engineering</Heading>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 m-3">
                <NuxtLink :to="buildRoute('/dashboard/preengineerings/program_of_work', 'for_pow')">
                    <DashboardStatCard title="For Pre-engineering" subtitle="Total projects for pre-engineering"
                        :count="counts.for_preengineering" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/preengineerings/quality_control', 'for_qcp')">
                    <DashboardStatCard title="For QCP" subtitle="Total projects for QCP" :count="counts.for_qcp" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/preengineerings/review', 'for_review')">
                    <DashboardStatCard title="For Review" subtitle="Total projects for review"
                        :count="counts.for_review" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/preengineerings/program_of_work', 'processed_pow')">
                    <DashboardStatCard title="Processed POW" subtitle="Total processed POW"
                        :count="counts.processed_pow" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/preengineerings/program_of_work', 'pow_assignments')">
                    <DashboardStatCard title="Assigned" subtitle="Assigned POW" :count="counts.pow_assignments" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/preengineerings/quality_control', 'qcp_assignments')">
                    <DashboardStatCard title="Assigned" subtitle="Assigned QCP" :count="counts.qcp_assignments" />
                </NuxtLink>
            </div>
        </div>

        <div v-if="user.role === '1' || user.role === 'benroStaff' || user.role === 'benroChief'">
            <Heading as="h4">Environmental Clearance</Heading>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 m-3">
                <NuxtLink :to="buildRoute('/dashboard/environmental_clearances', 'for_processing')">
                    <DashboardStatCard title="For processing of EC" subtitle="For environmental clearance"
                        :count="counts.for_ec" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/environmental_clearances', 'on_process')">
                    <DashboardStatCard title="Ongoing EC" subtitle="Ongoing environmental clearance"
                        :count="counts.ongoing_ec" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/environmental_clearances', 'clearance_issued')">
                    <DashboardStatCard title="Processed EC" subtitle="Processed environmental clearance"
                        :count="counts.processed_ec" />
                </NuxtLink>

                <NuxtLink :to="buildRoute('/dashboard/environmental_clearances', 'ec_assignments')">
                    <DashboardStatCard title="Assigned" subtitle="Current pending assignments"
                        :count="counts.ec_assignments" />
                </NuxtLink>


            </div>
        </div>

        <div class="w-full px-4">
            <Heading as="h4">Projects per Fund Source</Heading>
            <div class="flex flex-row w-full gap-6 mt-4">
                <div class="flex-1 bg-cardbg shadow rounded-lg p-6 border border-jetblack">
                    <table class="min-w-full border rounded-lg overflow-hidden">
                        <thead class="bg-cardbg-300">
                            <tr>
                                <th class="px-4 py-2 border-b border-jetblack text-left">Fund Source</th>
                                <th class="px-4 py-2 border-b border-jetblack text-center">Projects Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in fundSourceCounts" :key="item.fundsource_id">
                                <td class="px-4 py-2 border-b border-jetblack">{{ item.fundsource.fundsource }}</td>
                                <td class="px-4 py-2 border-b border-jetblack text-center">{{ item.total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex-1 bg-cardbg border border-jetblack shadow rounded-lg p-6">
                    <Card class="border border-cardbg">
                        <CardContent class="flex justify-center items-center bg-cardbg">
                            <div class="w-[400px] h-[400px]">
                                <Pie v-if="chartData.labels.length" :data="chartData" :options="chartOptions" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 m-3">
                    <div v-for="fundsource in projectCountsByFundsource" :key="fundsource.id">
                        <DashboardStatCard :title="fundsource.name" :count="fundsource.count"
                            @card-click="() => navigateToFilteredFundedProjects(fundsource.id)" />
                    </div>
                </div>
            </div>
        </div>
        <Separator class="my-6" />
        <div class="w-full rounded-lg px-6">
            <Heading as="h4">Projects per Municipality</Heading>
            <div class="flex flex-row w-full gap-6 mt-4">
                <div class="flex-1 bg-cardbg shadow rounded-lg p-6">
                    <table class="min-w-full rounded-lg overflow-hidden">
                        <thead class="bg-cardbg-300">
                            <tr>
                                <th class="px-4 py-2 border-b border-jetblack text-left">Municipality</th>
                                <th class="px-4 py-2 border-b border-jetblack text-center">Projects Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in municipalityCounts" :key="item.municipality">
                                <td class="px-4 py-2 border-b border-jetblack">{{ item.municipality }}</td>
                                <td class="px-4 py-2 border-b border-jetblack text-center">{{ item.total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex-1 bg-cardbg shadow rounded-lg p-6">
                    <Card class="border border-cardbg">
                        <CardContent class="flex justify-center items-center bg-cardbg">
                            <div class="w-[400px] h-[400px]">
                                <Pie v-if="chartDataMunicipality.labels.length" :data="chartDataMunicipality"
                                    :options="chartOptions" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
        <!-- <Separator class="my-6" />
        <div class="grid grid-cols-4 md:grid-cols-4 gap-2 flex-grow">
            <Card>
                <CardHeader>
                    <CardDescription>Funded Projects</CardDescription>
                </CardHeader>
                <CardContent>
                    No. of funded projects
                    <h2
                        class="scroll-m-20 border-b pb-2 text-3xl font-semibold tracking-tight transition-colors first:mt-0">
                        {{ count_funded_projects }}</h2>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardDescription>Validated Funded Projects</CardDescription>
                </CardHeader>
                <CardContent>
                    No. of validated funded projects
                    <h2
                        class="scroll-m-20 border-b pb-2 text-3xl font-semibold tracking-tight transition-colors first:mt-0">
                        {{ count_validated_funded_projects }}</h2>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardDescription>Programmed Funded Projects</CardDescription>
                </CardHeader>
                <CardContent>
                    No. of programmed funded projects
                    <h2
                        class="scroll-m-20 border-b pb-2 text-3xl font-semibold tracking-tight transition-colors first:mt-0">
                        {{ count_programmed_funded_projects }}</h2>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardDescription>Ongoing Procurement</CardDescription>
                </CardHeader>
                <CardContent>
                    No. of funded projects w/ ongoing procurement
                </CardContent>
            </Card>
        </div> -->

    </div>
</template>

<script setup>
definePageMeta({
    middleware: 'auth',
})

const { user, me } = useAuth()

const { api } = useAxios()

import { Pie } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const currentYear = new Date().getFullYear()

const years = Array.from(
    { length: 20 },
    (_, i) => {
        const year = currentYear - i
        return {
            id: year,
            name: String(year),
        }
    }
)

const filterForm = reactive({
    'yearFunded': null,
})

const buildRoute = (path, filter) => {
    return {
        path,
        query: {
            filter,
            yearFunded: filterForm.yearFunded
        }
    }
}


// watch(user, (newVal) => {
//     if (newVal) {
//         fetchValidationAssignments(newVal.employee_id)
//     } else {
//         console.log("No user logged in")
//     }
// })

const counts = reactive({
    project_proposals: 0,
    validated_proposals: 0,
    unvalidated_proposals: 0,
    funded_proposals: 0,
    validation_my_assignments: 0,
    funded_projects: 0,
    validated_funded_projects: 0,
    preengineered_projects: 0,
    for_preengineering: 0,
    for_qcp: 0,
    for_review: 0,
    processed_pow: 0,
    for_ec: 0,
    ongoing_ec: 0,
    processed_ec: 0,
    ec_assignments: 0,
    pow_assignments: 0,
    qcp_assignments: 0,
})

const fetchDashboardCounts = async () => {
    const response = await api.get('/api/dashboardCounts', {
        params: {
            yearFunded: filterForm.yearFunded,
            employeeId: user.value.employee_id,
        }
    })
    Object.assign(counts, response.data)
}



const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: "bottom",
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    const label = context.label || ""
                    const value = context.raw
                    const total = context.chart._metasets[0].total
                    const percentage = ((value / total) * 100).toFixed(1)
                    return `${label}: ${value} (${percentage}%)`
                }
            }
        }
    }
}


const fundSourceCounts = ref({})
const chartData = ref({
    labels: [],
    datasets: []
})

const fetchFundSourceCounts = async () => {
    const { data } = await api.get('/api/dashboard/count_by_fundsource')
    fundSourceCounts.value = data
    chartData.value = {
        labels: data.map(item => item.fundsource.fundsource),
        datasets: [
            {
                data: data.map(item => item.total),
                backgroundColor: ['#8884d8', '#82ca9d', '#ffc658', '#ff7f7f', '#00C49F', '#a4de6c', '#d0ed57']
            }
        ]
    }
}

const municipalityCounts = ref([])
const chartDataMunicipality = ref({
    labels: [],
    datasets: []
})
const fetchMunicipalityCounts = async () => {
    const { data } = await api.get('/api/dashboard/count_by_municipality')
    municipalityCounts.value = data
    chartDataMunicipality.value = {
        labels: data.map(item => item.municipality),
        datasets: [
            {
                data: data.map(item => item.total),
                backgroundColor: ['#8884d8', '#82ca9d', '#ffc658', '#ff7f7f', '#00C49F', '#a4de6c', '#d0ed57', '#8dd1e1', '#83a6ed', '#d888d8', '#c6b5e9', '#f7a8a8', '#f5d0a9']
            }
        ]
    }
}

const unvalidatedFunded = computed(() => {
    if (counts.funded_projects == null || counts.validated_funded_projects == null) {
        return 0
    }
    return counts.funded_projects - counts.validated_funded_projects
})

const chartDataValidatedFunded = computed(() => ({
    labels: ["Validated", "Not Validated"],
    datasets: [
        {
            data: [
                counts.validated_funded_projects ?? 0,
                unvalidatedFunded.value ?? 0
            ],
            backgroundColor: ["#A5D6A7", "#EF9A9A"],
        },
    ],
}))

chartDataValidatedFunded.value = {
    labels: ["Validated", "Not Validated"],
    datasets: [
        {
            data: [counts.validated_funded_projects, unvalidatedFunded.value],
            backgroundColor: ["#A5D6A7", "#EF9A9A"],
        },
    ],
}

const unprogrammedFunded = computed(() => {
    if (counts.funded_projects == null || counts.preengineered_projects == null) {
        return 0
    }
    return counts.funded_projects - counts.preengineered_projects
})

const chartDataProgrammedFunded = computed(() => ({
    labels: ["Programmed", "Unprogrammed"],
    datasets: [
        {
            data: [
                counts.preengineered_projects ?? 0,
                unprogrammedFunded.value ?? 0
            ],
            backgroundColor: ["#A5D6A7", "#EF9A9A"],
        },
    ],
}))

chartDataProgrammedFunded.value = {
    labels: ["Programmed", "Unprogrammed"],
    datasets: [
        {
            data: [counts.preengineered_projects, unprogrammedFunded.value],
            backgroundColor: ["#A5D6A7", "#EF9A9A"],
        },
    ],
}

const fetchAllCounts = async () => {
    await Promise.all([
        me(),
        fetchDashboardCounts(),
    ])
}

const handleFilterFormSubmit = async () => {
    await fetchAllCounts()
}


onMounted(async () => {
    fetchAllCounts()
    fetchFundSourceCounts()
    fetchMunicipalityCounts()
})

definePageMeta({
  middleware: ['auth']
})

</script>