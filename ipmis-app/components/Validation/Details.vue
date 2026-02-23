<template>
    <TopDrawer class="max-h-[70vh] overflow-auto bg-drawerbg">
        <template #header>
            <h5>Project Details</h5>
        </template>
        <div>
            <!-- <div>
                <h6 class="font-mono text-md">Project:</h6>
                <div class="flex justify-between justify-end pt-4">
                    <div class="px-4">
                        <p class="text-sm text-muted-foreground">Title:</p>
                        <p class="text-sm font-medium">{{ project?.title }}
                        </p>
                    </div>
                    <div class="px-4">
                        <p class="text-sm text-muted-foreground">Cost:</p>
                        <p class="text-sm font-medium">{{ Number(project?.cost).toLocaleString('en-US', {
                            minimumFractionDigits: 2, maximumFractionDigits: 2
                        }) }}
                        </p>
                    </div>
                    <div class="px-4">
                        <p class="text-sm text-muted-foreground">Project Type:</p>
                        <p class="text-sm font-medium">{{ mappedProjectType }}
                        </p>
                    </div>
                    <div class="px-4">
                        <p class="text-sm text-muted-foreground">Location:</p>
                        <p class="text-sm font-medium">{{ mappedLocations }}
                        </p>
                    </div>
                </div>
            </div> -->
            <ProjectDetails :project="project" @manage-other-requirements="manageOtherRequirements" />
            <Separator class="bg-gray-300 mt-2" />
            <div v-if="validationDetails?.data !== null">
                <h2 v-if="validation_status === 'Validated'" class="font-mono text-lg text-center pt-4">Validation:</h2>
                <h2 v-else class="font-mono text-lg text-center pt-4">No Validation Data</h2>
                <div class="flex flex-row w-full">
                    <div v-if="validation_status === 'Validated'" class="flex flex-row flex-grow p-2">
                        <div class="flex gap-2 p-2 w-full">
                            <div class="flex-1 bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Beneficiaries</h3>
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

                            <div class="flex-1 bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Responsible Persons</h3>
                                <p class="text-sm text-muted-foreground">Responsible persons:</p>
                                <div v-for="responsible_person in validationDetails?.responsible_people">
                                    <p class="text-sm font-medium">{{ responsible_person?.employee }}</p>

                                </div>
                            </div>

                            <div class="flex-1 bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Other Details</h3>
                                <InfoItem label="Present During Validation"
                                    :value="validationDetails?.data?.present_during_validation" />
                                <InfoItem label="Actions/Recommendations"
                                    :value="validationDetails?.data?.actions_recommendations" />
                                <InfoItem label="Remarks" :value="validationDetails?.data?.remarks" />
                            </div>

                            <div class="flex-1 bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Files</h3>
                                <span v-if="validationDetails?.files.length > 0">
                                    <p class="text-sm text-muted-foreground">Files</p>
                                    <span v-for="file in validationDetails?.files">
                                        <p class="text-sm font-medium">
                                            <a :href="file.file_path" target="_blank" rel="noopener noreferrer"
                                                class="text-blue-600 underline">
                                                {{ file.file_path.split('/').pop() }}
                                            </a>
                                        </p>
                                    </span>
                                </span>
                                <span v-else>
                                    <p class="text-sm text-muted-foreground">No Files</p>
                                </span>
                            </div>
                        </div>
                        <div>
                            <ClientOnly>
                                <ImageMapHolder v-if="showImageMap" v-model:isOpen="showImageMap"
                                    :images="validationDetails?.images" />
                            </ClientOnly>
                            <ClientOnly>
                                <ImageHolder v-if="showImage" v-model:isOpen="showImage"
                                    :images="validationDetails?.map_images" />
                            </ClientOnly>
                        </div>
                    </div>
                    <div v-if="validation_assignment === 'Assigned' && validation_status === 'Unvalidated'"
                        class="flex justify-center w-full py-4">
                        <Button variant="newprimary" @click="inputValidation">
                            <TicketCheck /> Validate
                        </Button>
                    </div>
                    <div class="flex flex-col gap-2 items-start px-2 flex-shrink-0 w-fit">
                        <Button variant="newprimary" v-if="validation_status === 'Validated'" @click="editValidation">
                            <FilePenLineIcon />Edit Validation
                        </Button>
                        <Button variant="newprimary" v-if="validationDetails?.images.length > 0"
                            @click="showImageMap = true">
                            <Image /> View Images
                        </Button>
                        <Button variant="newprimary" v-if="validationDetails?.map_images.length > 0"
                            @click="showImage = true">
                            <Map /> View Map Images
                        </Button>
                    </div>
                </div>
                <Separator class="bg-gray-300 mt-2 mb-10" />
                <DetailStepper :stepsMap="stepsMap" :generic_length="validationDetails" />
            </div>
            <div v-else class="flex flex-col items-center justify-center flex-grow p-4 text-center space-y-5">
                <div v-if="project.validation_assignment === 'Unassigned'">
                    <Button variant="newprimary" @click="assignValidation">
                        <FilePenLineIcon />Assign
                    </Button>
                </div>
            </div>
        </div>
    </TopDrawer>
</template>

<script setup>
import { FilePenLineIcon, TicketCheck, Image, Map, Blocks } from 'lucide-vue-next';
import DetailStepper from '@/components/DetailStepper.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import ImageMapHolder from '@/components/ImageMapHolder.vue';

const props = defineProps({
    project: Object,
    drawerKey: null
})

const emit = defineEmits(['assign', 'validate', 'edit-validation', 'manage-other-requirements'])

const showImageMap = ref(false)
const showImage = ref(false)

const validationDetails = ref({})
const validation_assignment = ref(null)
const validation_status = ref(null)

watch(() => props.project, (newProject) => {
    validationDetails.value = newProject?.validation
    validation_assignment.value = newProject?.validation_assignment
    validation_status.value = newProject?.validation_status
}, { immediate: true })

const stepsMap = computed(() => {
    const result = {}
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

const mappedLocations = computed(() => {
    const locations = props.project?.locations

    if (typeof locations === 'string') {
        return locations
    }

    if (!Array.isArray(locations) || locations.length === 0) {
        return 'No location'
    }

    return locations
        .map(loc => [loc.sitio, loc.barangay, loc.municipality].filter(Boolean).join(', '))
        .join('; ')
})

const mappedProjectType = computed(() => {
    const projectType = props.project?.project_type

    if (typeof projectType === 'string') {
        return projectType
    } else {
        return projectType.project_type
    }
})

const assignValidation = () => {
    emit('assign')
}

const inputValidation = () => {
    emit('validate')
}

const editValidation = () => {
    emit('edit-validation')
}

const manageOtherRequirements = () => {
    emit('manage-other-requirements', props.project)
}

onMounted(() => {
    console.log("Project props", props.project)
})
</script>

<style lang="scss" scoped></style>