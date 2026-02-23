<template>
    <RightDrawer v-model:isOpen="isDrawerOpen" @update:isOpen="emit('update:isFundedProjectDrawerOpen', $event)">
        <template #header>
            <div class="custom-header">
                <h2 class="font-sans text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c => c.toUpperCase())
                }} funded project</h2>
            </div>
        </template>
        <form @submit.prevent="handleSubmit">
            <div v-if="mode === 'add'" class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                <FormLabel>Project Title</FormLabel>
                <FormCombobox :options="projectProposals" placeholder="Select project"
                    v-model="fundedProjectForm.selectedProject" />
            </div>
            <div class="mb-8">
                <FormGroup placeholder="Project Title" v-model="fundedProjectForm.title" :errorMessage="errorBag.title"
                    name="project_title" label="Title" @blur="checkDuplicateTitle" />

                <p v-if="titleExists" class="text-red-500 text-sm mt-1">
                    {{ titleMessage }}
                </p>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-8">
                <div>
                    <FormLabel>Is By Admin</FormLabel>
                    <Switch v-model="isAdmin" />
                </div>
                <FormGroup placeholder="Year Funded" v-model="fundedProjectForm.year_funded"
                    :errorMessage="errorBag.year_funded" name="year_funded" label="Year Funded" type="number" min="2000"
                    max="2026" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-8">
                <div>
                    <FormLabel>Has Supplemental Budget</FormLabel>
                    <Switch v-model="hasSupplementalBudget" />
                </div>
                <FormGroup placeholder="SB Number(if applicable)" v-model="fundedProjectForm.sb_number"
                    :errorMessage="errorBag.sb_number" name="sb_number" label="SB Number" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow mb-8">
                <div>
                    <FormLabel>Fund Source</FormLabel>
                    <FormCombobox :options="fundSources" placeholder="Select fund source"
                        v-model="fundedProjectForm.fundsource_id" />
                </div>
                <!-- <FormGroup placeholder="Project Cost" v-model="fundedProjectForm.approved_cost"
                    :errorMessage="errorBag.approved_cost" name="approved_cost" label="Cost" /> -->

                <CleaveInput v-model="fundedProjectForm.approved_cost" label="Approved Cost" labelFor="approved_cost"
                    name="approved_cost" placeholder="Approved Cost" :errorMessage="errorBag.approved_cost"
                    :options="{ numeral: true, numeralThousandsGroupStyle: 'thousand', numeralDecimalScale: 2 }" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow mb-8">
                <div>
                    <FormLabel class="mb-2">Project Number</FormLabel>
                    <FormPrefixInput :prefix="`${fundedProjectForm.year_funded || '0000'}-${pn_prefix}`"
                        v-model="fundedProjectForm.number" @blur="setRefNumber" />
                </div>

                <div>
                    <FormLabel class="mb-2">Reference Number</FormLabel>
                    <FormPrefixInput
                        :prefix="`${fundsource_code || '0'}${fundedProjectForm.sb_number || '0'}-${fundedProjectForm.year_funded || '0000'}${isAdmin ? '-ADMIN' : ''}`"
                        v-model="fundedProjectForm.reference_number" readonly />

                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 flex-grow mb-1">
                <FormLabel>Municipality</FormLabel>
                <FormLabel>Barangay</FormLabel>
                <FormLabel>Sitio</FormLabel>
            </div>

            <div v-for="(location, index) in fundedProjectForm.locations"
                class="grid grid-cols-1 md:grid-cols-4 gap-2 flex-grow mb-2">
                <FormCombobox :options="municipalities" placeholder="Select municipality"
                    v-model="location.municipality_id" @change="fetchBarangays(index)" />

                <FormCombobox :options="barangays" placeholder="Select barangay" v-model="location.barangay_id"
                    :name="'locations[' + index + '][barangay_id]'" :id="'barangay_' + index"
                    :disabled="!location.municipality_id" />

                <div>
                    <FormInput class="h-10.5 mt-2" placeholder="Sitio" v-model="location.sitio"
                        :name="'locations[' + index + '][sitio]'" :id="'sitio_' + index" />
                </div>

                <TrashIcon class="h-5 w-5 text-red-600" @click="removeLoc(index)" />
            </div>

            <div class="mb-8">
                <PlusIcon class="h-5 w-5 text-blue-600" @click="addLoc" />
            </div>

            <div class="flex flex-row justify-end">
                <Button type="submit" variant="newprimary" size="lg" :disabled="titleExists || isSubmitting">
                    <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                    <span v-else>Submit</span>
                </Button>
            </div>
        </form>
    </RightDrawer>

</template>

<script setup>
import RightDrawer from '@/components/RightDrawer.vue';
import { PlusIcon, TrashIcon } from 'lucide-vue-next';
import CleaveInput from '../Form/CleaveInput.vue';
import Spinner from '../Spinner.vue';

const { api } = useAxios()
const { errorBag } = useCustomError()
const { fundSources, fetchFundSources } = useFundSources()
const { projectProposals, fetchProjectProposals, project, fetchProject } = useProjects()
const { hasSupplementalBudget, isAdmin, fundedProjectForm, submitFundedProject, resetForm, isSubmitting } = useFundedProjects()

const props = defineProps({
    isFundedProjectDrawerOpen: { type: Boolean, default: false },
    mode: { type: String, default: 'add' },
    fundedProject: { type: Object, default: null }
})

const emit = defineEmits(['update:isFundedProjectDrawerOpen', 'add', 'form-submitted'])

const isDrawerOpen = ref(false)

watch(() => props.isFundedProjectDrawerOpen, (val) => {
    isDrawerOpen.value = val
})

watch(isDrawerOpen, (val) => {
    emit('update:isFundedProjectDrawerOpen', val)
})

const titleExists = ref(false)
const titleMessage = ref('')

const checkDuplicateTitle = async () => {
    if (!fundedProjectForm.title) {
        titleExists.value = false
        titleMessage.value = ''
        return
    }

    try {
        const { data } = await api.get('/api/funded_projects/check_title', {
            params: {
                title: fundedProjectForm.title,
                ignore_id: props.mode === 'edit'
                    ? props.fundedProject?.id
                    : null
            }
        })

        titleExists.value = data.exists
        titleMessage.value = data.exists
            ? 'Project title already exists.'
            : ''

    } catch (err) {
        console.error(err)
    }
}

const addLoc = () => {
    fundedProjectForm.locations.push({ municipality_id: '', barangay_id: '', sitio: '' })
}

const removeLoc = (index) => {
    fundedProjectForm.locations.splice(index, 1)
}

const municipalities = ref([])

function fetchMunicipalities() {
    api.get('/api/municipalities').then(({ data }) => {
        municipalities.value = data
    })
}

const barangays = ref([])

function fetchBarangaysByMunicipality(municipalityId) {
    api.get(`/api/municipalities/${municipalityId}/barangays`).then(({ data }) => {
        barangays.value = data
    })
}

function fetchBarangays(index) {
    console.log("Mun changed")
    const municipalityId = fundedProjectForm.locations[index].municipality_id
    const barangays = fetchBarangaysByMunicipality(municipalityId)
    fundedProjectForm.locations[index].barangays = barangays
}

watch(
    () => fundedProjectForm.locations,
    (locations) => {
        locations.forEach((loc, index) => {
            if (loc.municipality_id) {
                fetchBarangays(index)
            }
        })
    },
    { deep: true }
)
const handleSubmit = async () => {
    await submitFundedProject({
        mode: props.mode,
        projectId: props?.fundedProject.id || '',
        fundedProjectId: props.fundedProject?.latest_funding?.id,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            resetForm()
        }
    })
}

watch(() => fundedProjectForm.selectedProject,
    (newSelectedProject) => {
        fetchProject(newSelectedProject)
    }
)

watch(
    () => project.value,
    (newVal) => {
        if (newVal) {
            console.log('Selected Project:', newVal)

            Object.assign(fundedProjectForm, {
                title: newVal.title || '',
                fundsource_id: newVal.fundsource?.id || '',
                approved_cost: Number(newVal.cost).toLocaleString("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }),
                locations: newVal.locations || [
                    { municipality_id: '', barangay_id: '', sitio: '' }
                ]
            })
            fetchFundSources()
        }
    },
    { deep: true, immediate: true }
)

const fundsource_code = ref()
const fundsource_abbrev = ref()
watch(
    () => fundedProjectForm.fundsource_id,
    (newVal) => {
        if (newVal) {
            const fund = fundSources.value.find(f => f.id === newVal)
            fundsource_code.value = fund ? fund.fundsource_code : ''
            fundsource_abbrev.value = fund ? fund.fundsource_abbrev : ''
        } else {
            fundsource_code.value = ''
        }
    }
)
const prefix = () => {
    return `${fundsource_code.value ? fundsource_code.value : 0}${hasSupplementalBudget.value ? 1 : 0}`
}

watch(
    [fundsource_code, hasSupplementalBudget, isAdmin],
    ([newCode, newSupp, newAdmin]) => {
        if (!newCode) return

        let current = fundedProjectForm.reference_number || ""
        let parts = current.split("-")
        parts.shift()

        let suffix = parts.at(-1) || "XXX"

        let adminPart = isAdmin.value ? "-ADMIN" : ""

        fundedProjectForm.reference_number = `${prefix()}-${fundedProjectForm.year_funded}${adminPart}-${suffix}`
    }
)

const pn_prefix = computed(() => {

    let prefix = fundsource_code.value || '0'
    prefix += fundedProjectForm.sb_number ? fundedProjectForm.sb_number : '0'
    if (isAdmin.value) {
        prefix += '-ADMIN'
    }
    return prefix
})

const setRefNumber = async () => {
    // ⏳ Wait until FormPrefixInput finishes emitting and parent v-model updates
    await nextTick()

    const prefix = fundsource_code.value || ''
    const full = fundedProjectForm.number || ''

    // remove prefix and leading dash if it exists
    let userPart = full.startsWith(prefix)
        ? full.slice(prefix.length).replace(/^[-]/, '')
        : full

    // If format is like "12-2025", make the first part 3 digits
    userPart = userPart.replace(/^(\d{1,3})(.*)/, (_, num, rest) => {
        const padded = num.padStart(3, '0')
        return padded + rest
    })

    fundedProjectForm.reference_number = userPart
}


watch(
    [
        () => fundedProjectForm.year_funded,
        fundsource_abbrev,
        () => fundedProjectForm.sb_number
    ],
    ([newYear, newAbbrev, newSB]) => {
        if (!newYear || !newAbbrev) return

        const current = fundedProjectForm.number || ''
        const parts = current.split('-')
        const suffix = parts.at(-1) || '###'

        fundedProjectForm.number = `${newYear}-${pn_prefix.value}-${suffix}`
    }
)

watch(
    () => props.mode,
    async (mode) => {
        if (mode === 'edit' && props.fundedProject) {
            console.log("Project to edit: ", props.fundedProject)
            Object.assign(fundedProjectForm, {
                title: props.fundedProject.title || '',
                number: props.fundedProject.latest_funding.number || '',
                reference_number: props.fundedProject.latest_funding.reference_number,
                fundsource_id: props.fundedProject.latest_funding.fundsource_id || '',
                year_funded: props.fundedProject.latest_funding.year_funded || '',
                sb_number: props.fundedProject.latest_funding.sb_number || '',
                approved_cost: props.fundedProject.latest_funding.approved_cost || '',
                locations: props.fundedProject.locations_array || [
                    { municipality_id: '', barangay_id: '', sitio: '' }
                ]
            })
        } else if (mode === 'add') {
            resetForm()
        }
    },
    { immediate: true }
)



onMounted(() => {
    fetchProjectProposals()
    fetchMunicipalities()
})

</script>

<style lang="scss" scoped></style>