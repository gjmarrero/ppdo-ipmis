<template>
    <RightDrawer v-model:isOpen="isDrawerOpen" @update:isOpen="emit('update:isProjectDrawerOpen', $event)">
        <template #header>
            <div class="custom-header">
                <h2 class="font-sans text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c => c.toUpperCase())
                }} project</h2>
            </div>
        </template>
        <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                <FormGroup placeholder="Project title" v-model="projectForm.title" :errorMessage="errorBag.title"
                    name="project_title" label="Title" @blur="checkDuplicateTitle" />

                <p v-if="titleExists" class="text-red-500 text-sm mt-1">
                    {{ titleMessage }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow mb-8">
                <div>
                    <FormLabel>Project Type</FormLabel>
                    <FormCombobox :options="projectTypes" placeholder="Select type"
                        v-model="projectForm.project_type_id" :errorMessage="errorBag.project_type_id" />
                </div>
                <div>
                    <FormLabel>Fund Source</FormLabel>
                    <FormCombobox :options="fundSources" placeholder="Select fund source"
                        v-model="projectForm.fundsource_id" :errorMessage="errorBag.fundsource_id" />
                </div>
                <div>
                    <CleaveInput v-model="projectForm.cost" label="Project Cost" labelFor="cost" name="cost"
                        placeholder="Enter cost" :errorMessage="errorBag.cost"
                        :options="{ numeral: true, numeralThousandsGroupStyle: 'thousand', numeralDecimalScale: 2 }" />
                    <!-- <CleaveInput v-model="cost"
                        :options="{ numeral: true, numeralThousandsGroupStyle: 'thousand', numeralDecimalScale: 2 }" /> -->

                </div>
            </div>
            <div class="flex flex-col gap-4 mb-8">
                <FormLabel>Supporting Documents (i.e. Resolutions)</FormLabel>
                <div class="flex flex-col md:flex-row justify-between items-start gap-6">

                    <div>
                        <input type="file" id="fileUpload" multiple class="hidden" ref="fileInput"
                            @change="handleFileChange" />
                        <Button variant="newsecondary" type="button" @click="triggerFileInput">
                            Choose Files
                        </Button>
                    </div>

                    <div v-if="project?.supporting_documents?.length" class="md:text-left">
                        <p class="text-sm text-muted-foreground mb-1">Existing Files</p>
                        <div class="space-y-1">
                            <p v-for="(file, index) in project.supporting_documents" :key="index"
                                class="text-sm font-medium">
                                <a :href="file.file_path" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 underline">
                                    {{ file.file_path.split('/').pop() }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="files.length" class="flex flex-wrap mt-2">
                    <span v-for="(file, index) in files" :key="index"
                        class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2 flex items-center">
                        {{ file.name }}
                        <button @click.prevent="removeFile(index)" class="ml-2 text-xs text-white hover:text-gray-200">
                            &times;
                        </button>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 flex-grow mb-8">
                <FormLabel>Municipality</FormLabel>
                <FormLabel>Barangay</FormLabel>
                <FormLabel>Sitio</FormLabel>
            </div>

            <div v-for="(location, index) in projectForm.locations"
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
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Spinner from '../Spinner.vue';
import CleaveInput from '../Form/CleaveInput.vue';

const { api } = useAxios()
const { errorBag } = useCustomError()
const { projectForm, files, fileInput, submitProject, resetForm, isSubmitting } = useProjects()
const { projectTypes, fetchProjectTypes, error } = useProjectTypes()
const { fundSources, fetchFundSources } = useFundSources()

const props = defineProps({
    isProjectDrawerOpen: { type: Boolean, default: false },
    mode: { type: String, default: 'add' },
    project: { type: Object, default: null }
})

const emit = defineEmits(['update:isProjectDrawerOpen', 'add', 'form-submitted'])

const isDrawerOpen = ref(props.isProjectDrawerOpen)

watch(() => props.isProjectDrawerOpen, (val) => {
    isDrawerOpen.value = val
})

watch(isDrawerOpen, (val) => {
    emit('update:isProjectDrawerOpen', val)
})

const titleExists = ref(false)
const titleMessage = ref('')

const checkDuplicateTitle = async () => {
    if (!projectForm.title) {
        titleExists.value = false
        titleMessage.value = ''
        return
    }

    try {
        const { data } = await api.get('/api/funded_projects/check_title', {
            params: {
                title: projectForm.title,
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

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    files.value = Array.from(event.target.files);
};

const removeFile = (index) => {
    files.value.splice(index, 1);
};

const addLoc = () => {
    projectForm.locations.push({ municipality_id: '', barangay_id: '', sitio: '' })
}

const removeLoc = (index) => {
    projectForm.locations.splice(index, 1)
}

const handleSubmit = async () => {
    await submitProject({
        mode: props.mode,
        projectId: props.project?.id,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            resetForm()
        }
    })
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
        barangays.value = [...data].sort((a, b) => {
            const aName = (a.name ?? a.barangay ?? '').toString()
            const bName = (b.name ?? b.barangay ?? '').toString()
            return aName.localeCompare(bName, undefined, { sensitivity: 'base' })
        })
    })
}

function fetchBarangays(index) {
    console.log("Mun changed")
    const municipalityId = projectForm.locations[index].municipality_id
    const barangays = fetchBarangaysByMunicipality(municipalityId)
    projectForm.locations[index].barangays = barangays
}

watch(
    () => projectForm.locations,
    (locations) => {
        locations.forEach((loc, index) => {
            if (loc.municipality_id) {
                fetchBarangays(index)
            }
        })
    },
    { deep: true }
)

watch(
    () => props.mode,
    async (mode) => {
        if (mode === 'edit' && props.project) {
            console.log("Project to edit: ", props.project)
            Object.assign(projectForm, {
                title: props.project.title || '',
                fundsource_id: props.project.fundsource_id || '',
                project_type_id: props.project.project_type_id || '',
                cost: props.project.cost || '',
                locations: props.project.locations_array || [
                    { id: '', municipality_id: '', barangay_id: '', sitio: '' }
                ]
            })
            console.log("Form", projectForm)
        }
    },
    { immediate: true }
)

onMounted(() => {
    fetchMunicipalities()
    fetchFundSources()
    fetchProjectTypes()
})

</script>

<style lang="scss" scoped></style>