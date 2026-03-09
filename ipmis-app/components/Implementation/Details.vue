<template>
    <TopDrawer class="max-h-[60vh] overflow-auto">
        <template #header>
            <div class="custom-header flex items-center">
                <h2 class="font-mono text-lg font-semibold">View Implementation Data</h2>
            </div>
        </template>
        <div>
            <ProjectDetails :project="funded_project" @manage-other-requirements="manageOtherRequirements" />
        </div>

        <Separator class="my-4 bg-gray-300" />

        <div v-if="implementationDetails" class="space-y-6">
            <h2 class="font-mono text-lg text-center">Implementation Data</h2>
            <div class='grid grid-cols-1 gap-6 md:grid-cols-5'>
                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Initial Documents</h3>
                    <InfoItem label="Date Documents Received" :value="implementationDetails?.date_received" />
                    <InfoItem label="Project-in-Charge" :value="implementationDetails?.employee" />
                    <InfoItem label="Date Start" :value="implementationDetails?.date_start" />
                </div>

                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <div class="flex flex-row justify-between">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Schedule and Accomplishment</h3>
                        <PencilIcon class="h-5 w-5 cursor-pointer" @click="editSchedule" />
                    </div>
                    <div v-if="activeSchedulesWithActual.length > 0" class="space-y-2">
                        <div v-for="schedule in activeSchedulesWithActual" :key="schedule.id"
                            class="rounded border border-borderblackui p-2">
                            <p class="font-medium">{{ schedule.monthLabel }}</p>
                            <p class="text-sm">Target: {{ schedule.target }}</p>
                            <p class="text-sm">Actual: {{ schedule.actualLabel }}</p>
                        </div>
                    </div>
                    <p v-else>No monthly schedule set</p>
                </div>

                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <div class="flex flex-row justify-between border-b mb-4  pb-2">
                        <h3 class="text-lg font-semibold">Inspection Data</h3>
                        <PencilIcon class="h-5 w-5 cursor-pointer" @click="editInspection" />
                    </div>
                    <InfoItem label="Date Request Received" :value="latestInspectionDetails?.date_request_received ?? '-'" />
                    <InfoItem label="Date of Pre-inspection" :value="latestInspectionDetails?.date_pre_inspection ?? '-'" />
                    <InfoItem label="Date of Post-inspection" :value="latestInspectionDetails?.date_post_inspection ?? '-'" />
                    <InfoItem label="Date of Final Inspection" :value="latestInspectionDetails?.date_final_inspection ?? '-'" />
                    <InfoItem label="Date Report Prepared" :value="latestInspectionDetails?.date_report_prepared ?? '-'" />
                    <InfoItem label="Date File Compilation Prepared" :value="latestInspectionDetails?.date_file_compilation_prepared ?? '-'" />
                    <InfoItem label="Remarks" :value="latestInspectionDetails?.remarks ?? '-'" />
                    <div>
                        <p class="text-sm text-muted-foreground">Attachment:</p>
                        <div v-if="latestInspectionDetails?.files?.length" class="space-y-1">
                            <a v-for="file in latestInspectionDetails.files" :key="file.id"
                                :href="resolveFileUrl(file.file_path)" target="_blank" rel="noopener noreferrer"
                                class="block w-full break-all whitespace-normal text-sm font-medium text-blue-600 hover:underline">
                                {{ file.file_path?.split('/').pop() ?? '-' }}
                            </a>
                        </div>
                        <p v-else class="text-sm font-medium">No data</p>
                    </div>
                </div>


            </div>
            <div class='grid grid-cols-1 gap-6'>
                <div class="bg-cardbg border border-borderblackui rounded-lg shadow p-4 space-y-3 md:col-span-3">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">
                        Order Details
                    </h3>
                    <div v-if="implementationOrders && implementationOrders.length > 0">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Action</TableHead>
                                    <TableHead>Order</TableHead>
                                    <TableHead>DTS Barcode</TableHead>
                                    <TableHead>Date Prepared</TableHead>
                                    <TableHead>Date Checked</TableHead>
                                    <TableHead>Date Reviewed</TableHead>
                                    <TableHead>Date Recommended for Approval</TableHead>
                                    <TableHead>Date Forwarded to LCE</TableHead>
                                    <TableHead>Date Approved by LCE</TableHead>
                                    <TableHead>Date Signed by Contractor</TableHead>
                                    <TableHead>File</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="implementationOrder in implementationOrders"
                                    :key="implementationOrder.id">
                                    <TableCell class="w-1/4">
                                        <Button variant="newprimary" size="sm" @click="editOrder(implementationOrder)">
                                            <PencilIcon class="h-4 w-4" />
                                        </Button>
                                    </TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.order_type }} </TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.dts_barcode }} </TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_prepared }} </TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_checked }}</TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_reviewed }}</TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_recommended_for_approval }}
                                    </TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_forwarded_to_lce }}
                                    </TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_approved_by_lce }}</TableCell>
                                    <TableCell class="w-1/4"> {{ implementationOrder.date_signed_by_contractor }}
                                    </TableCell>
                                    <TableCell class="w-1/2">
                                        <div v-if="implementationOrder.files?.length" class="space-y-1">
                                            <a v-for="file in implementationOrder.files" :key="file.id"
                                                :href="resolveFileUrl(file.file_path)" target="_blank"
                                                rel="noopener noreferrer" class="block w-full break-all whitespace-normal text-blue-600 hover:underline">
                                                {{ file.file_path?.split('/').pop() ?? '-' }}
                                            </a>
                                        </div>
                                        <span v-else>-</span>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                    <p v-else>No implementation orders found</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 justify-end">
                <Button @click="editImplementation" variant="newprimary">
                    <FilePenLineIcon />Edit Implementation
                </Button>
                <div v-if="!isProcurementLoading && (procurements?.project_details || []).length > 0"
                    class="flex justify-end gap-1">
                    <Button variant="newprimary"
                        @click="activeAccomplishments.length === 0 ? addSchedule() : editSchedule()">
                        <CalendarClock />
                        {{ activeAccomplishments.length === 0 ? 'Add Schedule & Target' : 'Edit Schedule & Target'
                        }}
                    </Button>
                    <Button @click="addAccomplishment" variant="newprimary">
                        <CalendarCheck2 /> Add Accomplishment
                    </Button>
                    <Button @click="addOrder" variant="newprimary">
                        <ChartBarStacked /> Add Order
                    </Button>
                    <Button v-if="accomplishmentsCompleted" @click="scheduleInspection" variant="newprimary">
                        <CalendarCheck2 /> Schedule Inspection
                    </Button>
                    <Button @click="qualityControl" variant="newprimary">
                        <CalendarCheck2 /> Quality Control
                    </Button>
                </div>
            </div>
            <Separator class="bg-gray-300" />
            <div class="flex flex-row flex-grow max-h-[250px] p-2">
                <div v-if="activeAccomplishments.length > 0" class="w-full flex flex-row items-center mt-10">
                    <DetailStepper :stepsMap="stepsMap" :generic_length="implementationDetails" />
                </div>
                <div v-else class="w-full flex flex-row items-center mt-10">
                    <p> No monthly schedule set </p>
                </div>
            </div>
            <Separator class="bg-gray-300" />

            <div v-if="!isProcurementLoading && (procurements?.project_details || []).length === 0">
                <AlertNotice variant="warning" title="Warning"
                    message="No procurement data for this project. You cannot proceed with the scheduling." />
            </div>
            <div v-else>
                <p>{{ latestProjectDetail?.duration }}</p>
            </div>
        </div>
        <div v-else class="flex justify-center items-center">
            <Button @click="addImplementation">
                <Leaf /> Add Implementation Data
            </Button>
        </div>
    </TopDrawer>
</template>

<script setup>
import { CalendarCheck2, CalendarClock, ChartBarStacked, FilePenLineIcon, Leaf, PencilIcon } from 'lucide-vue-next'
const { procurements, fetchProcurementDetails } = useProcurements()
const rtConfig = useRuntimeConfig()
const props = defineProps({
    funded_project: Object
})

const emit = defineEmits(['add-implementation', 'edit-implementation', 'add-schedule', 'edit-schedule', 'add-accomplishment', 'add-order', 'edit-order', 'schedule-inspection', 'edit-inspection', 'manage-other-requirements', 'quality-control'])

const implementationDetails = computed(() => props.funded_project?.latest_funding?.latest_implementation)

const latestProjectDetail = computed(() => {
    const details = procurements.value?.project_details
    if (!details || !details.length) return null

    return details.at(-1)
})


const activeAccomplishments = computed(() => {
    return (implementationDetails.value?.monthly_schedule_accomplishment || [])
        .filter(item => item.is_active)
        .sort((a, b) => new Date(a.month) - new Date(b.month))
})

const activeSchedulesWithActual = computed(() => {
    return activeAccomplishments.value.map(item => ({
        id: item.id,
        monthLabel: formatMonthYear(item.month),
        target: item.target ?? 'N/A',
        actualLabel: item.actual ?? 'Pending'
    }))
})

const implementationOrders = computed(() => implementationDetails.value?.implementation_orders || [])
const implementationInspections = computed(() => implementationDetails.value?.implementation_inspections || [])

const latestSchedule = computed(() => {
    if (!activeAccomplishments.value.length) return null

    const sorted = [...activeAccomplishments.value].sort((a, b) => new Date(b.month) - new Date(a.month))

    return sorted[0]
})

const latestInspectionDetails = computed(() => {
    if (!implementationInspections.value.length) return null

    const sorted = [...implementationInspections.value].sort((a, b) => {
        const aDate = new Date(a.date_request_received || a.created_at || 0).getTime()
        const bDate = new Date(b.date_request_received || b.created_at || 0).getTime()
        return bDate - aDate
    })

    return sorted[0]
})


const stepsMap = computed(() => {
    const result = {}
    const accomplishments = activeAccomplishments.value

    if (accomplishments?.length > 0) {
        const steps = []

        const lastIndex = accomplishments.findLastIndex(step => step.actual)

        accomplishments.forEach((item, index) => {
            let state = "inactive"

            if (index < lastIndex) {
                state = "completed"
            } else if (index === lastIndex) {
                state = "active"
            }

            steps.push({
                step: index,
                title: new Date(item.month).toLocaleString("default", {
                    month: "long",
                    year: "numeric"
                }),
                description: `Target: ${item.target}, Actual: ${item.actual ?? "Pending"}`,
                state
            })
        })

        result[accomplishments[0].implementation_id] = steps
    } else {
        console.log("Schedule", implementationDetails.value)
    }

    return result
})

const accomplishmentsCompleted = computed(() => {
    const acc = activeAccomplishments.value
    if (!acc || !acc.length) return false
    return acc.every(a => a.actual !== null && a.actual !== undefined)
})

const scheduleInspection = () => {
    emit('schedule-inspection')
}

const qualityControl = () => {
    emit('quality-control')
}

const editInspection = () => {
    if (!latestInspectionDetails.value) return
    emit('edit-inspection', latestInspectionDetails.value)
}


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

const formatMonthYear = (dateStr) => {
    if (!dateStr) return 'No data'
    return new Date(dateStr).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long'
    })
}

const resolveFileUrl = (filePath) => {
    if (!filePath) return '#'
    if (/^https?:\/\//i.test(filePath)) return filePath
    if (filePath.startsWith('/')) return `${rtConfig.public.API_URL}${filePath}`
    return `${rtConfig.public.API_URL}/storage/${filePath}`
}

const addImplementation = () => {
    emit('add-implementation')
}

const editImplementation = () => {
    emit('edit-implementation')
}

const addAccomplishment = () => {
    console.log("implementation id", implementationDetails.value?.id)
    emit('add-accomplishment', implementationDetails.value?.id)
}

const addSchedule = () => {
    console.log("Add schedule", latestProjectDetail.value?.duration)
    emit('add-schedule', latestProjectDetail.value?.duration)
}

const editSchedule = () => {
    console.log("Edit schedule", latestProjectDetail.value?.duration)
    emit('edit-schedule', latestProjectDetail.value?.duration, implementationDetails.value?.id)
}

const addOrder = () => {
    emit('add-order')
}

const editOrder = (order) => {
    emit('edit-order', order)
}

const manageOtherRequirements = () => {
    emit('manage-other-requirements', props.funded_project)
}

const isProcurementLoading = ref(false)

onMounted(async () => {
    isProcurementLoading.value = true
    await fetchProcurementDetails(props.funded_project?.title)
    isProcurementLoading.value = false
    console.log("Full props", props.funded_project)
    console.log("Imp details", implementationDetails.value)
    console.log("Proc details", procurements.value)
    console.log("Latest Proc Proj Detail", latestProjectDetail.value)
    console.log("Latest Schedule", latestSchedule.value)
    console.log("Latest Inspection", latestInspectionDetails.value)
})

</script>

<style lang="scss" scoped></style>
