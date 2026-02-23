<template>
    <TopDrawer class="max-h-[65vh] overflow-auto">
        <template #header>
            <div class="custom-header flex items-center">
                <h2 class="font-mono text-lg font-semibold">View Preengineering Status (APE & PE)</h2>
            </div>
        </template>
        <div>
            <ProjectDetails :project="funded_project" @manage-other-requirements="manageOtherRequirements" />
        </div>
        <Separator class="mb-2 mt-2 bg-gray-200" />
        <div v-if="preEngineeringDetails">
            <span class="font-mono text-justify-center">Pre-engineering Details</span>

            <div class="flex gap-4 p-4 items-stretch">
                <div class="w-[89.5%] grid grid-cols-6 gap-x-8 gap-y-4">
                    <div>
                        <p class="text-sm text-muted-foreground">Date of Data Gathering:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.date_conducted || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Name of Programmer:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.employee || 'No data' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">Indicator:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.indicator || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Programmed Cost:</p>
                        <p class="text-sm font-medium">
                            {{
                                Number(preEngineeringDetails?.programmed_cost).toLocaleString(
                                    'en-US',
                                    { minimumFractionDigits: 2, maximumFractionDigits: 2 }
                                ) || 'No data'
                            }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">POW Date Prepared:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.date_prepared_pow || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">POW Date Checked:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.date_checked_pow || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Date Received by QC</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.date_received_by_qc }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Quality Control In-Charge</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.employee_qc || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">QCP Date Prepared</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.date_qcp_prepared || 'No data' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">QCP Date Reviewed</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.date_qcp_reviewed || 'No data' }}</p>
                    </div>

                    <div class="col-span-full">
                        <div class="grid grid-cols-6 gap-x-8 gap-y-4">
                            <div>
                                <p class="text-sm text-muted-foreground">Date Submitted to Division Head:</p>
                                <p class="text-sm font-medium">
                                    {{ preEngineeringDetails?.date_submitted_divhead || 'No data' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-muted-foreground">Date Approved by PE:</p>
                                <p class="text-sm font-medium">
                                    {{ preEngineeringDetails?.date_approved_pe || 'No data' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-muted-foreground">Date Submitted to LCE:</p>
                                <p class="text-sm font-medium">
                                    {{ preEngineeringDetails?.date_submitted_lce || 'No data' }}
                                </p>
                            </div>
                        </div>
                    </div>
<!-- 


                    <div v-if="!preEngineeringDetails?.date_received_by_qc">
                        <p class="text-sm text-muted-foreground">Current Status:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.latest_activity || 'No data' }}</p>
                    </div>

                    <div v-if="!preEngineeringDetails?.date_received_by_qc">
                        <p class="text-sm text-muted-foreground">Current Status:</p>
                        <p class="text-sm font-medium">{{ preEngineeringDetails?.latest_activity || 'No data' }}</p>
                    </div> -->

                    <div class="col-span-full">
                        <p class="text-sm text-muted-foreground">Files:</p>
                        <p class="text-sm font-medium">
                            {{ preEngineeringDetails?.documents ? 'No Files Available' : '' }}
                        </p>
                    </div>
                </div>
                <Separator orientation="vertical" class="h-auto w-[1px] bg-gray-300" />
                <div class="w-[11.5%] flex flex-col gap-6">
                    <div class="flex flex-col gap-2 items-start px-2 flex-shrink-0 w-fit">
                        <Button size="md" @click="updateReviewStatus">
                            <FilePenLineIcon />Update Review Status
                        </Button>
                        <div v-if="hasPreEngineeringImages">
                            <Button size="md" @click="showImageMap = true">
                                <Image />View Images
                            </Button>
                        </div>

                        <Button size="md" @click="addActivity()">
                            <Footprints />Update Status
                        </Button>
                    </div>
                </div>
            </div>

            <Separator class="bg-gray-300 my-3" />

            <div class="flex flex-row flex-grow max-h-[250px]">
                <div class="w-5/6 flex flex-row items-center mt-10">
                    <DetailStepper :stepsMap="stepsMap" :generic_length="preEngineeringDetails" />
                </div>

                <div v-if="!preEngineeringDetails?.date_received_by_qc" class="w-1/6 overflow-auto px-5 py-5">
                    <Separator orientation="vertical" class="h-auto w-[1px] bg-gray-300" />
                    <VerticalStepper v-if="stepsMapPreEngActivities[preEngineeringDetails.id]"
                        :steps="stepsMapPreEngActivities[preEngineeringDetails.id]" />

                </div>
            </div>

            <div class="flex flex-row flex-grow p-4">
                <ClientOnly>
                    <ImageMapHolder v-if="showImageMap" v-model:isOpen="showImageMap"
                        :images="preEngineeringDetails?.images" />
                </ClientOnly>

            </div>
        </div>
        <div v-else class="flex justify-center items-center">
            <Button size="large" @click="addPreEngineering">
            <DraftingCompass />
                Input Pre-engineering data</Button>
        </div>
    </TopDrawer>
</template>

<script setup>
import { DraftingCompass, FilePenLineIcon, Footprints, Image } from 'lucide-vue-next';
import Button from '@/components/Button.vue';
import DetailStepper from '@/components/DetailStepper.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import ImageMapHolder from '@/components/ImageMapHolder.vue';
import VerticalStepper from '@/components/VerticalStepper.vue';

const { api } = useAxios()

const props = defineProps({
    funded_project: Object,
    drawerKey: null
})

const emit = defineEmits(['add-preengineering', 'update-review', 'open-activity-dialog', 'manage-other-requirements'])

const showImageMap = ref(false)

const preEngineeringDetails = computed(() => props.funded_project?.latest_funding?.latest_preengineering)

const hasPreEngineeringImages = computed(() => {
    return preEngineeringDetails.value?.images?.length > 0
})

const preEngineeringActivitiesMap = ref({})

const preengineeringId = ref(null)

const fetchPreEngineeringActivities = async (pre_engineering_id) => {
    const { data } = await api.get(`/api/fetch_preengineering_activities/${pre_engineering_id}`)
    preEngineeringActivitiesMap.value[pre_engineering_id] = data
    preengineeringId.value = pre_engineering_id
}

watch(
    () => preEngineeringDetails.value,
    (newDetails) => {
        if (!newDetails?.length) return

        for (const detail of newDetails) {
            fetchPreEngineeringActivities(detail.id)
        }
    },
    { immediate: true }
)

const stepsMapPreEngActivities = computed(() => {
    const result = {}
    for (const id in preEngineeringActivitiesMap.value) {
        let activities = preEngineeringActivitiesMap.value[id] || []
        const detail = preEngineeringDetails.value.find(d => d.id === id)

        if (detail?.date_submitted_divhead) {
            const cutoffIndex = activities.findIndex(
                (a) => a.activity === 'Submitted to Div Head'
            )

            if (cutoffIndex !== -1) {
                activities = activities.slice(0, cutoffIndex + 1)
            }
        }

        const steps = []

        steps.push({
            step: 0,
            title: 'POW preparation',
            description: 'Initial POW preparation',
            state: activities.length === 0 ? 'active' : 'completed'
        })

        const lastIndex = activities.length - 1

        activities.forEach((item, index) => {
            let state = 'inactive'
            if (index === lastIndex) {
                state = 'active'
            } else if (index < lastIndex) {
                state = 'completed'
            }

            steps.push({
                step: index + 1,
                title: item.activity,
                description: `By ${item.employee.first_name} ${item.employee.middle_initial || ''} ${item.employee.last_name} — ${formatDate(item.created_at)}`,
                state
            })
        })
        result[id] = steps.reverse()
    }
    return result
})
const stepsMap = computed(() => {
    const result = {}
    const detail = preEngineeringDetails.value?.[0]
    console.log("PreEng Details", detail)
    if (detail) {
        const data = detail
        const steps = []

        const timeline = [
            {
                key: 'date_conducted',
                title: 'Data Gathering Conducted',
                description: data.date_conducted
                    ? formatDate(data.date_conducted)
                    : 'Pending'
            },
            {
                key: 'date_prepared_pow',
                title: 'Date POW prepared',
                description: data.date_prepared_pow
                    ? formatDate(data.date_prepared_pow)
                    : 'Pending'
            },
            {
                key: 'date_checked_pow',
                title: 'Date POW checked',
                description: data.date_checked_pow
                    ? formatDate(data.date_checked_pow)
                    : 'Pending'
            },
            {
                key: 'date_received_by_qc',
                title: 'Received by QC',
                description: data.date_received_by_qc
                    ? formatDate(data.date_received_by_qc)
                    : 'Pending'
            },
            {
                key: 'date_qcp_prepared',
                title: 'QCP prepared',
                description: data.date_qcp_prepared
                    ? formatDate(data.date_qcp_prepared)
                    : 'Pending'
            },
            {
                key: 'date_qcp_reviewed',
                title: 'QCP reviewed',
                description: data.date_qcp_reviewed
                    ? formatDate(data.date_qcp_reviewed)
                    : 'Pending'
            },
            {
                key: 'date_submitted_divhead',
                title: 'Submitted to Div Head',
                description: data.date_submitted_divhead
                    ? formatDate(data.date_submitted_divhead)
                    : 'Pending'
            },
            {
                key: 'date_approved_pe',
                title: 'Approved by PE',
                description: data.date_approved_pe
                    ? formatDate(data.date_approved_pe)
                    : 'Pending'
            },
            {
                key: 'date_submitted_lce',
                title: 'Submitted to LCE',
                description: data.date_submitted_lce
                    ? formatDate(data.date_submitted_lce)
                    : 'Pending'
            },
            {
                key: 'date_approved_lce',
                title: 'Approved by LCE',
                description: data.date_approved_lce
                    ? formatDate(data.date_approved_lce)
                    : 'Pending'
            }
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

const addPreEngineering = () => {
    emit('add-preengineering')
}

const updateReviewStatus = () => {
    emit('update-review')
}
const addActivity = () => {
    emit('open-activity-dialog')
}

const manageOtherRequirements = () => {
    emit('manage-other-requirements', props.funded_project)
}

onMounted(() => {
    console.log("Received props", preEngineeringDetails.value)
})

</script>

<style lang="scss" scoped></style>