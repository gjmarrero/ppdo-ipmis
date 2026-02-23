<template>
    <form @click="addProject">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
            <FormGroup placeholder="Project title" v-model="projectForm.title" :errorMessage="errorBag.title"
                name="project_title" label="Title" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow mb-8">
            <div>
                <FormLabel>Project Type</FormLabel>
                <FormCombobox :options="projecttypes" placeholder="Select project type" v-model="projectForm.type" />
            </div>
            <div>
                <FormLabel>Fund Source</FormLabel>
                <FormCombobox :options="fundsources" placeholder="Select fund source"
                    v-model="projectForm.fundsource" />
            </div>
            <FormGroup placeholder="Project Cost" v-model="projectForm.cost" :errorMessage="errorBag.cost"
                name="project_cost" label="Cost" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
            <div>
                <FormLabel>Supporting Documents</FormLabel>
                <input type="file" id="fileUpload" multiple class="hidden" ref="fileInput" @change="handleFileChange" />
                <Button variant="secondary" type="button" @click="triggerFileInput"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Choose Files
                </Button>
            </div>
            <div v-if="files.length" class="flex flex-wrap mt-0">
                <span v-for="(file, index) in files" :key="index"
                    class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2">
                    {{ file.name }}
                    <button @click.prevent="removeFile(index)" class="ml-1 text-xs text-white">
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
            <FormCombobox :options="municipalities" placeholder="Select municipality" v-model="location.municipality_id"
                @change="fetchBarangays(index)" />

            <FormCombobox :options="barangays" placeholder="Select barangay" v-model="location.barangay_id"
                :name="'locations[' + index + '][barangay_id]'" :id="'barangay_' + index"
                :disabled="!location.municipality_id" />

            <FormInput class="h-10.5 mt-2" placeholder="Sitio" v-model="location.sitio"
                :name="'locations[' + index + '][sitio]'" :id="'sitio_' + index" />

            <TrashIcon class="h-5 w-5 text-red-600" @click="removeLoc(index)" />
        </div>

        <div class="mb-8">
            <PlusIcon class="h-5 w-5 text-blue-600" @click="addLoc" />
        </div>
        <Button type="submit" variant="primary">Submit</Button>
    </form>

</template>

<script setup>
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Button from '~/components/Button.vue';

const { api } = useAxios()

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const projectForm = reactive({
    type: '',
    title: '',
    cost: '',
    locations: [
        { municipality_id: '', barangay_id: '', sitio: '' }
    ]
})

const files = ref([]);

const fileInput = ref(null)

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

const emit = defineEmits(['add'])

function addProject() {
    const formDataObject = new FormData();

    // Append non-repeating fields
    formDataObject.append('status', projectForm.status);
    formDataObject.append('title', projectForm.title);
    formDataObject.append('cost', projectForm.cost);

    // Append repeating fields (locations)
    projectForm.locations.forEach((location, index) => {
        formDataObject.append(`locations[${index}][barangay_id]`, location.barangay_id);
        formDataObject.append(`locations[${index}][sitio]`, location.sitio);
    });

    files.value.forEach((file) => {
        formDataObject.append('files[]', file)
    })
    resetErrorBag()
    api.post('/api/project', formDataObject, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    }).then(({ data }) => {
        emit('add')
    }).catch(err => {
        transformValidationErrors(err.response)
    })
};

const fundsources = ref([])

function fetchFundsources() {
    api.get('/api/fetchFundsources').then(({ data }) => {
        fundsources.value = data
    })
}

const projecttypes = ref([])

function fetchProjectTypes() {
    api.get('/api/fetchProjectTypes').then(({ data }) => {
        projecttypes.value = data
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
        barangays.value = data
    })
}

function fetchBarangays(index) {
    const municipalityId = projectForm.locations[index].municipality_id

    const barangays = fetchBarangaysByMunicipality(municipalityId)
    projectForm.locations[index].barangays = barangays

}

watch(
    () => projectForm.locations,
    (newMunicipalityIds) => {
        newMunicipalityIds.forEach((newId, index) => {
            if (newId) {
                fetchBarangays(index);
            }
        });
    },
    { deep: true }
);

watch(() => projectForm.status,
    (newStatus) => {
        if (newStatus == 'funded') {
            console.log('Project funded')
        } else if (newStatus === 'for_validation') {
            console.log('Project for validation')
        }
    }
)

fetchMunicipalities()
fetchFundsources()
fetchProjectTypes()

</script>