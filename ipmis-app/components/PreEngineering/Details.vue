<template>
    <TopDrawer class="max-h-[65vh] overflow-auto bg-drawerbg text-textprimary">
        <template #header>
            <div class="custom-header flex items-center">
                <h2 class="font-mono text-lg font-semibold">View Preengineering Status</h2>
            </div>
        </template>
        <div>
            <ProjectDetails :project="funded_project" @manage-other-requirements="manageOtherRequirements" />
        </div>
        <Separator class="mb-2 mt-2" />
        <div v-if="preEngineeringDetails" class="space-y-6">

            <h2 class="font-mono text-lg text-center">Pre-engineering Data</h2>

            <div class='grid grid-cols-1 gap-6 md:grid-cols-5'>
                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">POW Information</h3>
                    <InfoItem label="Date of Data Gathering" :value="preEngineeringDetails?.date_conducted" />
                    <InfoItem label="Name of Programmer" :value="preEngineeringDetails?.employee" />
                    <InfoItem label="Programmed Cost" :value="formatCurrency(preEngineeringDetails?.programmed_cost)" />
                    <InfoItem label="POW Date Prepared" :value="preEngineeringDetails?.date_prepared_pow" />
                    <InfoItem label="POW Date Checked" :value="preEngineeringDetails?.date_checked_pow" />
                    <InfoItem label="POW Date Reviewed" :value="preEngineeringDetails?.date_reviewed_pow" />
                </div>

                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Indicator / Scope of Work</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="scope in preEngineeringDetails?.scopes" :key="scope.id"
                            class="p-2 rounded shadow-sm">
                            <p class="font-medium">{{ scope.scope_of_work }}</p>
                            <p class="text-sm text-muted-foreground">{{ scope.description }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Quality Control</h3>
                    <InfoItem label="Date Received by QC" :value="preEngineeringDetails?.date_received_by_qc" />
                    <InfoItem label="QC In-Charge" :value="preEngineeringDetails?.employee_qc" />
                    <InfoItem label="QCP Date Prepared" :value="preEngineeringDetails?.date_qcp_prepared" />
                    <InfoItem label="QCP Date Reviewed" :value="preEngineeringDetails?.date_qcp_reviewed" />
                </div>

                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Review and Recommendation</h3>
                    <InfoItem label="Date Received by APE" :value="preEngineeringDetails?.date_received_by_ape" />
                    <InfoItem label="Date Reviewed" :value="preEngineeringDetails?.date_reviewed" />
                    <InfoItem label="Date Recommended for Approval"
                        :value="preEngineeringDetails?.date_recommended_for_approval" />
                    <InfoItem label="Date Submitted to LCE" :value="preEngineeringDetails?.date_submitted_to_lce" />
                </div>

                <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Files</h3>
                    <p class="text-sm font-medium">
                        {{ preEngineeringDetails?.documents?.length ? 'Files Available' : 'No Files Available' }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 justify-end">
                <Button variant="newprimary"
                    v-if="preEngineeringDetails && route.path === '/dashboard/preengineerings/program_of_work'"
                    @click="revisePreEngineering">
                    <FilePenLineIcon /> Revise POW
                </Button>
                <Button variant="newprimary" v-if="showPow" @click="editPreEngineering">
                    <FilePenLineIcon /> Edit Pre-engineering
                </Button>
                <Button variant="newprimary" v-if="showQc" @click="updateQcpStatus">
                    <FilePenLineIcon /> Update QCP Status
                </Button>
                <Button variant="newprimary" v-if="showReview" @click="updateReviewStatus">
                    <FilePenLineIcon /> Update Review Status
                </Button>
                <Button variant="newprimary" v-if="hasPreEngineeringImages" @click="showImageMap = true">
                    <Image /> View Images
                </Button>
                <!-- <Button variant="newprimary" @click="addActivity()">
                    <Footprints /> Update Status
                </Button> -->
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <DetailStepper :stepsMap="stepsMap" :generic_length="preEngineeringDetailsLength" />
                </div>
                <div v-if="!preEngineeringDetails?.date_received_by_qc" class="w-full md:w-1/6 overflow-auto">
                    <VerticalStepper v-if="stepsMapPreEngActivities[preEngineeringDetails.id]"
                        :steps="stepsMapPreEngActivities[preEngineeringDetails.id]" />
                </div>
            </div>
            <Separator orientation="horizontal" />
            <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition py-4">
                <Table>
                    <TableCaption class="caption-top font-mono text-lg text-center mt-0 text-textsecondary">History of
                        Pre-engineering</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Date of Data Gathering</TableHead>
                            <TableHead>Programmer</TableHead>
                            <TableHead>Programmed Cost</TableHead>
                            <TableHead>Date POW Prepared</TableHead>
                            <TableHead>Date POW Checked</TableHead>
                            <TableHead>Date Received by QC</TableHead>
                            <TableHead>Date QCP Prepared</TableHead>
                            <TableHead>Date QCP Reviewed</TableHead>
                            <TableHead>Date Received by APE</TableHead>
                            <TableHead>Date Recommended for Approval</TableHead>
                            <TableHead>Date Submitted to LCE</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="preengineering in funded_project?.latest_funding?.preengineerings"
                            :key="preengineering.id">
                            <TableCell>{{ preengineering?.date_conducted }}</TableCell>
                            <TableCell>{{ preengineering?.employee }}</TableCell>
                            <TableCell>{{ preengineering?.programmed_cost }}</TableCell>
                            <TableCell>{{ preengineering?.date_prepared_pow }}</TableCell>
                            <TableCell>{{ preengineering?.date_checked_pow }}</TableCell>
                            <TableCell>{{ preengineering?.date_received_by_qc }}</TableCell>
                            <TableCell>{{ preengineering?.date_qcp_prepared }}</TableCell>
                            <TableCell>{{ preengineering?.date_qcp_reviewed }}</TableCell>
                            <TableCell>{{ preengineering?.date_received_by_ape }}</TableCell>
                            <TableCell>{{ preengineering?.date_recommended_for_approval }}</TableCell>
                            <TableCell>{{ preengineering?.date_submitted_to_lce }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <ClientOnly>
                <ImageMapHolder v-if="showImageMap" v-model:isOpen="showImageMap"
                    :images="preEngineeringDetails?.images" />
            </ClientOnly>
        </div>

        <div v-else class="flex justify-center items-center my-4">
            <Button variant="newprimary" @click="addPreEngineering">
                <DraftingCompass />
                {{ buttonLabel }}
            </Button>
        </div>

        <div class="py-4">
            <PreEngineeringSiteProblemDetails v-if="funded_project?.latest_funding?.latest_site_problem"
                :project="funded_project" />
        </div>
    </TopDrawer>
</template>

<script setup>
import { DraftingCompass, FilePenLineIcon, Footprints, Image } from 'lucide-vue-next';
import DetailStepper from '../DetailStepper.vue';
import Separator from '../ui/separator/Separator.vue';
import ImageMapHolder from '../ImageMapHolder.vue';
import VerticalStepper from '../VerticalStepper.vue';
import Table from '../ui/table/Table.vue';
import InfoItem from '../InfoItem.vue';

const { api } = useAxios()
const route = useRoute()

const props = defineProps({
    funded_project: Object,
    drawerKey: null,
    showPow: Boolean,
    showQc: Boolean,
    showReview: Boolean,
})

const emit = defineEmits(['add-preengineering', 'edit-preengineering', 'open-activity-dialog', 'manage-other-requirements', 'update-qcp', 'update-review', 'revise-pow'])

const formatCurrency = (amount) => {
    if (!amount && amount !== 0) return 'No data';
    return Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

const buttonLabel = computed(() => {
    switch (true) {
        case route.path.includes('quality_control'):
            return 'Input QC data'
        case route.path.includes('review'):
            return 'Input review data'
        default:
            return 'Input Pre-engineering data'
    }
})


const showImageMap = ref(false)

const preEngineeringDetails = computed(() => props.funded_project?.latest_funding?.latest_preengineering)
const preEngineeringDetailsLength = computed(() => preEngineeringDetails.value ? 1 : 0)
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
    const detail = preEngineeringDetails.value
    console.log("Steps Length", detail.length)
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

const editPreEngineering = () => {
    emit('edit-preengineering')
}
const addActivity = () => {
    emit('open-activity-dialog')
}

const manageOtherRequirements = () => {
    emit('manage-other-requirements', props.funded_project)
}

const updateQcpStatus = () => {
    emit('update-qcp')
}

const updateReviewStatus = () => {
    emit('update-review')
}

const revisePreEngineering = () => {
    emit('revise-pow')
}
onMounted(() => {
    console.log("Received funded_project props", props.funded_project)
})

</script>

<style lang="scss" scoped></style>