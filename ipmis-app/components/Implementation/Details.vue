<template>
    <TopDrawer class="max-h-[60vh] overflow-auto">
        <template #header>
            <div class="custom-header flex items-center">
                <h2 class="font-mono text-lg font-semibold">View Implementation Data</h2>
            </div>
        </template>
        <div v-if="implementationDetails">
            <!-- <div v-for="implementation in implementationDetails" :key="implementation.id"> -->
            <div class="flex justify-end my-2 gap-1">
                <Button @click="editImplementation">
                    <FilePenLineIcon />Edit Implementation
                </Button>
                <div v-if="!isProcurementLoading && (procurements?.project_details || []).length > 0"
                    class="flex justify-end gap-1">
                    <Button
                        @click="implementationDetails?.monthly_schedule_accomplishment.length === 0 ? addSchedule() : editSchedule()">
                        <CalendarClock />
                        {{ implementationDetails?.monthly_schedule_accomplishment.length === 0 ? 'Add Schedule & Target' : 'Edit Schedule & Target' }}
                    </Button>
                    <Button @click="addAccomplishment">
                        <CalendarCheck2 /> Add Accomplishment
                    </Button>
                    <Button @click="addOrder">
                        <ChartBarStacked /> Add Order
                    </Button>

                </div>
            </div>
            <div class="flex gap-4 p-4 items-stretch">
                <div class="w-1/2 flex flex-row gap-8 justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">Date documents received:</p>
                        <p class="text-sm font-medium">{{ implementationDetails?.date_received || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Project-in-charge:</p>
                        <p class="text-sm font-medium">{{ implementationDetails?.employee || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Date Start:</p>
                        <p class="text-sm font-medium">{{ implementationDetails?.date_start || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Status:</p>
                    </div>
                </div>
                <Separator orientation="vertical" class="h-auto w-[1px] bg-gray-300" />
            </div>
            <Separator class="bg-gray-300" />
            <div class="flex flex-row flex-grow max-h-[250px] p-2">
                <div v-if="implementationDetails.monthly_schedule_accomplishment.length > 0" class="w-full flex flex-row items-center mt-10">
                    <DetailStepper :stepsMap="stepsMap" :generic_length="implementationDetails" />
                </div>
                <div v-else class="w-full flex flex-row items-center mt-10">
                    <p> No monthly schedule set </p>
                </div>
            </div>
            <!-- </div> -->
            <Separator class="bg-gray-300" />
            <div class="my-2" v-if="implementationDetails?.implementation_orders.length > 0">
                <AlertNotice variant="info" title="Notice">
                    <p v-for="order in implementationDetails?.implementation_orders">With {{ order.order_type }} Order - DTS Barcode: {{ order.dts_barcode }} </p>                    
                </AlertNotice>
            </div>
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
import { CalendarCheck2, CalendarClock, ChartBarStacked, FilePenLineIcon, Leaf } from 'lucide-vue-next'
const { procurements, fetchProcurementDetails } = useProcurements()
const props = defineProps({
    funded_project: Object
})

const emit = defineEmits(['add-implementation', 'edit-implementation', 'add-schedule', 'edit-schedule', 'add-accomplishment', 'add-order'])

const implementationDetails = computed(() => props.funded_project?.latest_funding?.latest_implementation)

const latestProjectDetail = computed(() => {
    const details = procurements.value?.project_details
    if (!details || !details.length) return null

    return details.at(-1)
})


const stepsMap = computed(() => {
    const result = {}
    const accomplishments = implementationDetails.value?.monthly_schedule_accomplishment

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

const isProcurementLoading = ref(false)

onMounted(async () => {
    isProcurementLoading.value = true
    await fetchProcurementDetails(props.funded_project?.title)
    isProcurementLoading.value = false
    console.log("Full props", props.funded_project)
    console.log("Imp details", implementationDetails.value)
    console.log("Proc details", procurements.value)
    console.log("Latest Proc Proj Detail", latestProjectDetail.value)
})

</script>

<style lang="scss" scoped></style>