<template>
    <TopDrawer class="max-h-[60vh] overflow-auto">
        <template #header>
            <div class="custom-header flex items-center">
                <h2 class="font-mono text-lg font-semibold">View Environmental Clearance Status</h2>
            </div>
        </template>
        <div class="flex justify-between items-start my-2">
            <div class="flex-1 space-y-4">

                <ProjectDetails :project="funded_project" @manage-other-requirements="manageOtherRequirements" />

                <Separator class="bg-gray-200 my-4" />

                <div v-if="environmentalClearanceDetails" class="space-y-6">

                    <h2 class="font-mono text-lg text-center">
                        Environmental Clearance
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div class="bg-cardbg border border-borderblackui rounded-lg shadow p-4 space-y-3">
                            <h3 class="font-semibold border-b pb-2">
                                Certificate Information
                            </h3>

                            <InfoItem label="Certificate Type"
                                :value="environmentalClearanceDetails?.certificate_type" />
                            <InfoItem label="Date of Application"
                                :value="environmentalClearanceDetails?.date_application" />
                            <InfoItem label="Date Issued" :value="environmentalClearanceDetails?.date_issued" />
                        </div>

                        <div class="bg-cardbg border border-borderblackui rounded-lg shadow p-4 space-y-3">
                            <h3 class="font-semibold border-b pb-2">
                                Transmission to PEO
                            </h3>

                            <InfoItem label="Transmitted to PEO"
                                :value="environmentalClearanceDetails?.is_transmitted_peo === 1 ? 'Yes' : 'No'" />
                            <InfoItem label="Date Transmitted"
                                :value="environmentalClearanceDetails?.date_transmitted" />
                        </div>

                        <div class="bg-cardbg border border-borderblackui rounded-lg shadow p-4 space-y-3">
                            <h3 class="font-semibold border-b pb-2">
                                Files
                            </h3>

                            <template v-if="environmentalClearanceDetails.files?.length">
                                <div v-for="file in environmentalClearanceDetails.files" :key="file.id" class="text-sm">
                                    <span class="font-medium">
                                        {{ toUpperCase(file.type) }}:
                                    </span>
                                    <a :href="file.file_path" target="_blank" rel="noopener noreferrer"
                                        class="text-blue-600 underline ml-1">
                                        {{ file.file_path.split('/').pop() }}
                                    </a>
                                </div>
                            </template>

                            <p v-else class="text-sm text-muted-foreground">
                                No Files Available
                            </p>

                            <div v-if="funded_project?.latest_funding?.latest_preengineering?.approved_pow"
                                class="pt-3 border-t">
                                <p class="text-sm font-semibold text-muted-foreground mb-1">
                                    Approved POW
                                </p>
                                <a :href="funded_project.latest_funding.latest_preengineering.approved_pow"
                                    target="_blank" rel="noopener noreferrer" class="text-blue-600 underline text-sm">
                                    {{
                                        funded_project.latest_funding.latest_preengineering.approved_pow
                                            .split('/')
                                            .pop()
                                    }}
                                </a>
                            </div>
                        </div>

                        <div v-if="environmentalClearanceDetails.cmrs && environmentalClearanceDetails.cmrs.length > 0"
                            class="bg-cardbg border border-borderblackui rounded-lg shadow p-4 space-y-3">
                            <h3 class="font-semibold border-b pb-2">
                                CMR Details
                            </h3>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="w-1/4">Date Prepared</TableHead>
                                        <TableHead class="w-1/4">Date Submitted</TableHead>
                                        <TableHead class="w-1/2">File</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="cmr in environmentalClearanceDetails.cmrs">
                                        <TableCell class="w-1/4"> {{ cmr.date_prepared }} </TableCell>
                                        <TableCell class="w-1/4"> {{ cmr.date_submitted }}</TableCell>
                                        <TableCell class="w-1/2"><a v-if="cmr.file_path" :href="cmr.file_path"
                                                target="_blank" rel="noopener noreferrer"
                                                class="text-blue-600 hover:underline"> {{
                                                    cmr.file_path?.split('/').pop() ?? '—' }}</a></TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-end">
                        <Button @click="editEnvironmentalClearance" variant="newprimary">
                            <FilePenLineIcon />
                            Edit Environmental Clearance
                        </Button>
                        <Button @click="updateCmr" variant="newprimary">
                            <PaperclipIcon />
                            Update CMR
                        </Button>
                    </div>

                    <div class="rounded-lg shadow p-4">
                        <DetailStepper :stepsMap="stepsMap" :generic_length="environmentalClearanceDetails" />
                    </div>

                </div>

                <div v-else class="flex justify-center py-6">
                    <Button @click="addEnvironmentalClearance" variant="newprimary">
                        <Leaf />
                        Add Environmental Clearance Data
                    </Button>
                </div>
            </div>
        </div>
    </TopDrawer>
</template>

<script setup>
import { Blocks, FilePenLineIcon, Leaf, PackagePlus, PaperclipIcon } from 'lucide-vue-next'
const props = defineProps({
    funded_project: Object
})

const emit = defineEmits(['add-environmental-clearance', 'edit-environmental-clearance', 'manage-other-requirements', 'update-cmr'])

const environmentalClearanceDetails = computed(() => props.funded_project?.latest_funding?.latest_environmental_clearance)

const stepsMap = computed(() => {
    const result = {}
    const detail = environmentalClearanceDetails.value?.[0]
    if (detail) {
        const data = detail
        const steps = []

        const timeline = [
            {
                key: 'date_application',
                title: 'Date of Application',
                description: data.date_application
                    ? formatDate(data.date_application)
                    : 'Pending'
            },
            {
                key: 'date_issued',
                title: 'Date Issued',
                description: data.date_issued
                    ? formatDate(data.date_issued)
                    : 'Pending'
            },
        ]

        const lastIndex = timeline.findLastIndex(step => data[step.key])

        timeline.forEach((item, index) => {
            let state = 'inactive'
            if (index < lastIndex) {
                state = 'completed'
            } else if (index === lastIndex) {
                state = 'active'
            }

            steps.push({
                step: index,
                title: item.title,
                description: item.description,
                state
            })
        })

        result[detail.id] = steps
    }

    return result
})

const formatDate = (dateStr) => {
    const date = new Date(dateStr)
    return date.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit'
    })
}
const addEnvironmentalClearance = () => {
    emit('add-environmental-clearance')
}
const editEnvironmentalClearance = () => {
    emit('edit-environmental-clearance')
}

const manageOtherRequirements = () => {
    emit('manage-other-requirements', props.funded_project)
}

const updateCmr = () => {
    emit('update-cmr')
}

function toUpperCase(str) {
    return str
        .replace(/_/g, '/')
        .split('/')
        .map(word => word.toUpperCase())
        .join('/')
}

onMounted(() => {
    console.log("Project for ecc", props.funded_project)
    console.log("EnviDetails", environmentalClearanceDetails.value)
})

</script>

<style lang="scss" scoped></style>