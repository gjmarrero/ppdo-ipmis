<template>
    <TopDrawer>
        <template #header>
            <div class="custom-header flex items-center">
                <p>Funded project details</p>
            </div>
        </template>
        <Separator class="bg-gray-300" />
        <div class="flex justify-end my-2">
            <Button>
                <FilePenLineIcon />Edit
            </Button>
        </div>
        <div v-if="view_type === 'validation'">
            <div class="flex gap-4 p-4">
                <div class="w-1/4 flex flex-row flex-grow gap-8">
                    <div>
                        <p class="text-sm text-muted-foreground">Beneficiaries:</p>
                        <div v-for="beneficiaries in validationDetails?.beneficiaries">
                            <div>
                                <p class="text-sm font-medium">{{ beneficiaries?.beneficiary_type }} : {{
                                    beneficiaries?.beneficiary }}
                                </p>
                            </div>
                            <div v-for="sdd in beneficiaries?.beneficiary_sdd">
                                <p class="text-sm text-muted-foreground indent-3">Female : {{ sdd.female }}</p>
                                <p class="text-sm text-muted-foreground indent-3">Male : {{ sdd.male }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div>
                            <p class="text-sm text-muted-foreground">Present during validation:</p>
                            <p class="text-sm font-medium">{{ validationDetails.data.present_during_validation }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Actions/Recommendations:</p>
                        <p class="text-sm font-medium">{{ validationDetails.data.actions_recommendations }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Remarks:</p>
                        <p class="text-sm font-medium">{{ validationDetails.data.remarks }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">Responsible persons:</p>
                        <div v-for="responsible_person in validationDetails?.responsible_people">
                            <p class="text-sm font-medium">{{ responsible_person?.employee }}</p>
                        </div>
                    </div>


                </div>

                <div class="bg-gray-300 w-px self-stretch"></div>
                <Separator orientation="vertical" class="self-stretch" />
                <div class="w-1/4">
                    <span v-if="!validationDetails?.files">
                        <p class="text-sm text-muted-foreground">No files available</p>
                    </span>
                    <span v-else>
                        <p class="text-sm text-muted-foreground">Files:</p>
                        <span v-for="file in validationDetails?.files">
                            <p class="text-sm font-medium">
                                <a :href="file.file_path" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 underline">
                                    {{ file.file_path.split('/').pop() }}
                                </a>
                            </p>
                        </span>
                    </span>
                </div>

                <div class="bg-gray-300 w-px self-stretch"></div>
                <div class="w-1/4">
                    <span v-if="validationDetails?.images.length > 0">
                        <Button @click="showImageMap = true">View Images</Button>
                    </span>
                    <span v-else>
                        <p class="text-sm text-muted-foreground">No images</p>
                    </span>
                    <ImageMapHolder v-model:isOpen="showImageMap" :images="validationDetails?.images" />
                </div>
            </div>

            <Separator class="bg-gray-300 my-2" />
            <Stepper :stepsMap="stepsMap" :generic_length="validationDetails" />

        </div>
        <div v-if="view_type === 'preengineering'">
            <div v-for="preengineering in preEngineeringDetails" :key="preengineering.id">
                <div class="flex gap-4 p-4 items-stretch">
                    <div class="w-1/2 flex flex-row gap-8 justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">Name of Programmer:</p>
                            <p class="text-sm font-medium">{{ preengineering?.employee || 'No data' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Indicator:</p>
                            <p class="text-sm font-medium">{{ preengineering?.indicator || 'No data' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-muted-foreground">Programmed Cost:</p>
                            <p class="text-sm font-medium">{{ preengineering?.programmed_cost || 'No data' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Current Status:</p>
                            <p class="textsm font-medium">{{ preengineering?.latest_activity?.activity }}</p>
                        </div>
                    </div>
                    <Separator orientation="vertical" class="h-auto w-[1px] bg-gray-300" />

                    <div class="w-1/4 flex flex-row flex-grow gap-6">
                        <p class="text-sm text-muted-foreground">Files:</p>
                    </div>

                    <Separator orientation="vertical" class="h-auto w-[1px] bg-gray-300" />

                    <div class="w-1/4 flex flex-row flex-grow gap-6">
                        <span v-if="preengineering?.images.length > 0">
                            <Button>View Images</Button>
                        </span>
                        <span v-else>
                            <p class="text-sm font-medium">No images</p>
                        </span>
                    </div>
                </div>

                <Separator class="bg-gray-300 my-3" />

                <div class="flex flex-row flex-grow max-h-[250px]">
                    <div class="w-5/6 content-center">
                        <Stepper :stepsMap="stepsMap" :generic_length="preengineering" />
                    </div>

                    <Separator orientation="vertical" class="h-auto w-[1px] bg-gray-300" />

                    <div class="w-1/6 overflow-auto px-5">
                        <div class="flex justify-end my-2">
                            <Button @click="addActivity()">
                                <FilePenLineIcon />Update Status
                            </Button>
                        </div>
                        <VerticalStepper v-if="stepsMapPreEngActivities[preengineering.id]"
                            :steps="stepsMapPreEngActivities[preengineering.id]" />

                    </div>
                </div>
            </div>
        </div>

    </TopDrawer>
</template>

<script setup>
import { FilePenLineIcon } from 'lucide-vue-next';
import Button from '@/components/Button.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import ImageMapHolder from '@/components/ImageMapHolder.vue';
import VerticalStepper from '@/components/VerticalStepper.vue';

const { api } = useAxios()

const props = defineProps({
    funded_project: Object,
    view_type: String,
    drawerKey: null
})

const emit = defineEmits(['open-activity-dialog'])

const showImageMap = ref(false)

const validationDetails = ref({})

const preEngineeringDetails = computed(() => props.funded_project?.preengineering || [])

const preEngineeringActivitiesMap = ref({})

const preengineeringId = ref(null)

const fetchPreEngineeringActivities = async (pre_engineering_id) => {
    const { data } = await api.get(`/api/fetch_preengineering_activities/${pre_engineering_id}`)
    preEngineeringActivitiesMap.value[pre_engineering_id] = data
    preengineeringId.value = pre_engineering_id
}

watch(() => props.view_type, (newVal) => {
    console.log("Full project", props.funded_project)
    if (newVal === 'preengineering') {
    }
    else if (newVal === 'validation') {
        validationDetails.value = props.funded_project?.project?.validation
        console.log("Validation:", validationDetails.value)
    }
}, { immediate: true })

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
    if (props.view_type === 'preengineering') {
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
    }
})
const stepsMap = computed(() => {
    const result = {}
    if (props.view_type === 'preengineering') {

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
                    key: 'date_submitted_divhead',
                    title: 'Submitted to Division Head',
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
    } else if (props.view_type === 'validation') {
        const detail = validationDetails.value
        if (detail?.data) {
            const data = detail.data

            const steps = []

            const timeline = [
                {
                    key: 'date_assigned',
                    title: 'Validator Assigned',
                    description: data.date_assigned ? formatDate(data.date_assigned) : 'Pending'
                },
                {
                    key: 'date_validated',
                    title: 'Validation Conducted',
                    description: data.date_validated ? formatDate(data.date_validated) : 'Pending'
                },
                {
                    key: 'date_validation_report',
                    title: 'Report Submitted',
                    description: data.date_validation_report ? formatDate(data.date_validation_report) : 'Pending'
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
const addActivity = () => {
    emit('open-activity-dialog')
}

const viewValidationImages = async () => {
    console.log("View Images")
}

</script>

<style lang="scss" scoped></style>