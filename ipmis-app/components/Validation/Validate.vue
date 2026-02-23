<template>
    <RightDrawer v-model:isOpen="isOpen">
        <template #header>
            <div class="custom-header">
                <h2 class="font-mono text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c => c.toUpperCase())
                    }} validation details </h2>
            </div>
        </template>

        <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:gridt-cols-1 gap-2 flex-grow mb-8">
                <FormGroup placeholder="Project title" v-model="validationForm.title" :errorMessage="errorBag.title"
                    name="project_title" label="Title" />
                <FormGroup placeholder="Cost" v-model="validationForm.cost" :errorMessage="errorBag.cost" name="cost"
                    label="Cost" @blur="formatCost" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow mb-8">
                <FormGroup placeholder="Date assigned" v-model="validationForm.date_assigned"
                    :errorMessage="errorBag.date_assigned" name="date_assigned" label="Date Assigned" type="date" />
                <FormGroup placeholder="Date validated" v-model="validationForm.date_validated"
                    :errorMessage="errorBag.date_validated" name="date_validated" label="Date Validated" type="date" />
                <FormGroup placeholder="Date of validation report" v-model="validationForm.date_validation_report"
                    :errorMessage="errorBag.date_validation_report" name="date_validation_report"
                    label="Date of Validation Report" type="date" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                <FormLabel>Responsible persons</FormLabel>
                <FormMultiSelectCombobox :options="employees" placeholder="Select employees"
                    v-model="validationForm.responsible_persons" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-2">
                <div class="mb-8">
                    <FormLabel>Project Type</FormLabel>
                    <FormCombobox :options="projectTypes" placeholder="Select project type"
                        v-model="validationForm.project_type_id" />
                </div>
                <div v-if="validationForm.project_type_id" class="grid grid-cols-1 gap-2">
                    <div v-for="(beneficiary_type, index) in beneficiarytypesWithFlags" :key="index" :class="[
                        'grid gap-2 mb-2',
                        beneficiary_type.requiresGenderSplit ? 'grid-cols-3' : 'grid-cols-1 md:grid-cols-1'
                    ]">

                        <FormGroup :label="beneficiary_type?.beneficiary_type"
                            v-model="beneficiaryInputs[beneficiary_type.id]" type="text" />

                        <FormGroup v-if="beneficiary_type.requiresGenderSplit" label="Number of males"
                            v-model="validationForm.number_of_males" :errorMessage="errorBag.number_of_males"
                            name="number_of_males" type="text" />
                        <FormGroup v-if="beneficiary_type.requiresGenderSplit" label="Number of females"
                            v-model="validationForm.number_of_females" :errorMessage="errorBag.number_of_females"
                            name="number_of_females" type="text" />
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <FormLabel>Other Requirements</FormLabel>

                <div class="flex flex-wrap gap-4 mt-2 mb-4">
                    <div v-for="type in requirementTypes" :key="type.id" class="flex items-center space-x-2">
                        <Checkbox :id="type.id" :checked="selectedRequirementTypes.includes(type.id)"
                            @update:checked="toggleRequirementType(type.id, $event)" />
                        <label :for="type.id" class="text-sm text-textsecondary">
                            {{ type.label }}
                        </label>
                    </div>
                </div>
                <div v-if="selectedRequirementTypes.includes(pamb)">
                    <FormLabel>PAMB</FormLabel>
                    <FormCombobox :options="ecpambtypes" placeholder="Select PAMB" v-model="selectedPambType" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                <FormLabel>Present during validation</FormLabel>
                <Textarea placeholder="Present during validation" v-model="validationForm.present_during_validation"
                    :errorMessage="errorBag.present_during_validation" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                <FormLabel>Action/Recommendations</FormLabel>
                <Textarea placeholder="Actions" v-model="validationForm.actions_recommendations"
                    :errorMessage="errorBag.actions" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                <FormLabel>Remarks</FormLabel>
                <Textarea placeholder="Remarks" v-model="validationForm.remarks" :errorMessage="errorBag.remarks" />
            </div>
            <Separator />
            <div class="grid grid-cols-1 md:grid-cols-2 flex-grow my-8">
                <div>
                    <div>
                        <FormLabel>Validation Report</FormLabel>
                        <input type="file" id="fileUpload" multiple class="hidden" ref="fileInput"
                            @change="handleFileChange" />
                        <Button variant="newsecondary" type="button" @click="triggerFileInput">
                            Choose Files
                        </Button>
                    </div>
                    <div v-if="files.length" class="flex flex-wrap mt-4">
                        <span v-for="(file, index) in files" :key="index"
                            class="bg-info text-white px-3 py-1 rounded-full text-sm mr-2">
                            {{ file.name }}
                            <button @click.prevent="removeFile(index)" class="ml-1 text-xs text-white">
                                &times;
                            </button>
                        </span>
                    </div>
                </div>

                <div class="px-2">
                    <span v-if="project?.validation?.files.length > 0">
                        <p class="text-sm text-muted-foreground">Existing Files</p>
                        <span v-for="file in project?.validation?.files">
                            <p class="text-sm font-medium">
                                <a :href="file.file_path" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 underline">
                                    {{ file.file_path.split('/').pop() }}
                                </a>
                            </p>
                        </span>
                    </span>
                </div>

            </div>
            <Separator />
            <div class="grid grid-cols-1 md:grid-cols-2 flex-grow my-8">
                <div>
                    <div>
                        <FormLabel>Geotagged Photos</FormLabel>
                        <input ref="imageInput" type="file" accept="image/*" multiple class="hidden"
                            @change="handleImageChange" />

                        <Button variant="newsecondary" type="button" @click="triggerImageInput">
                            Choose Image
                        </Button>
                    </div>

                    <div v-if="images.length" class="grid grid-cols-1 gap-4 mt-4">
                        <div v-for="(image, index) in images" :key="index">
                            <div class="grid grid-cols-1 gap-2 mt-4 px-2">
                                <div class="flex flex-row justify-end">
                                    <button @click.prevent="removeImage(index)"
                                        class="absolute bg-red-600 text-white px-1.5 py-0.5 text-xs rounded-full shadow hover:bg-red-700">
                                        &times;
                                    </button>
                                </div>
                                <div class="flex flex-row justify-start">
                                    <div class="relative w-28 h-28">
                                        <img v-if="image.preview" :src="image.preview"
                                            class="object-cover w-full h-full rounded" />
                                    </div>
                                    <div class="ml-5 max-w-26">
                                        <FormGroup class="flex items-center gap-5" placeholder="X" v-model="image.lat"
                                            name="date_assigned" label="Lat" type="text" />
                                        <FormGroup class="flex items-center gap-2" placeholder="Y" v-model="image.long"
                                            name="date_assigned" label="Long" type="text" />
                                    </div>
                                    <div class="ml-5 max-w-24">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <span v-if="project?.validation?.images.length > 0">
                        <p class="text-sm text-muted-foreground">Existing Images</p>
                    </span>
                    <span v-for="image in project?.validation?.images">
                        <p class="text-sm font-medium">
                            <a :href="image.image_path" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 underline">
                                {{ image.image_path.split('/').pop() }}
                            </a>
                        </p>
                    </span>
                </div>
            </div>
            <Separator />
            <div class="grid grid-cols-1 md:grid-cols-2 flex-grow my-8">
                <div>
                    <div>
                        <FormLabel>Map Photos</FormLabel>
                        <input ref="mapImageInput" type="file" accept="image/*" multiple class="hidden"
                            @change="handleMapImageChange" />

                        <Button variant="newsecondary" type="button" @click="triggerMapImageInput">
                            Choose Image
                        </Button>
                    </div>

                    <div v-if="mapImages.length" class="grid grid-cols-1 gap-4 mt-4">
                        <div v-for="(image, index) in mapImages" :key="index">
                            <div class="relative w-28 h-28">
                                <img v-if="image.preview" :src="image.preview"
                                    class="object-cover w-full h-full rounded" />
                                <button @click.prevent="removeMapImage(index)"
                                    class="absolute top-1 right-1 bg-red-600 text-white px-1.5 py-0.5 text-xs rounded-full shadow hover:bg-red-700">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <span v-if="project?.validation?.map_images.length > 0">
                        <p class="text-sm text-muted-foreground">Existing Map Images</p>
                    </span>
                    <span v-for="image in project?.validation?.map_images">
                        <p class="text-sm font-medium">
                            <a :href="image.image_path" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 underline">
                                {{ image.image_path.split('/').pop() }}
                            </a>
                        </p>
                    </span>
                </div>
            </div>
            <Separator />
            <div class="flex flex-row justify-end mt-4">
                <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                    <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                    <span v-else>Submit</span>
                </Button>
            </div>

        </form>
    </RightDrawer>
</template>

<script setup>
import * as exifr from 'exifr'

const { api } = useAxios()

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const { projectTypes, fetchProjectTypes } = useProjectTypes()

const { employees, fetchEmployees } = useEmployees()

const { isSubmitting, errors, validationForm, files, fileInput, images, mapImages, imageInput, mapImageInput, beneficiaryInputs, requirementTypes, selectedRequirementTypes, selectedPambType, submitValidation, resetForm } = useValidations()

const props = defineProps({
    project: Object,
    validation: Object,
    modelValue: { type: Boolean, default: false },
    mode: {
        type: String,
        default: 'add'
    }
})

const emit = defineEmits(['add', 'form-submitted'])

const isOpen = computed({
    get: () => props.modelValue,
    set: val => emit('update:modelValue', val)
})

const ecpambtypes = ref([])

const fetchPambTypes = () => {
    api.get('/api/e_c_pamb_types').then(({ data }) => {
        ecpambtypes.value = data
    })
}

function formatCost() {
    let raw = String(validationForm.cost).replace(/,/g, '')
    if (raw.endsWith('.')) {
        return
    }

    const number = parseFloat(raw)

    if (!isNaN(number)) {
        if (raw.includes('.')) {
            const [intPart, decPart] = raw.split('.')
            const formattedInt = parseInt(intPart, 10).toLocaleString('en-US')
            validationForm.cost =
                decPart !== undefined ? `${formattedInt}.${decPart.slice(0, 2)}` : formattedInt
        } else {
            validationForm.cost = number.toLocaleString('en-US', {
                maximumFractionDigits: 0,
            })
        }
    } else {
        validationForm.cost = ''
    }
}


const beneficiarytypes = ref([])

const fetchBeneficiaryTypes = async (projectTypeId) => {
    if (!projectTypeId) return beneficiarytypes.value = [];

    try {
        const { data } = await api.get(`/api/project_types/${projectTypeId}/beneficiary_types`);
        beneficiarytypes.value = data.beneficiary_types;
    } catch (error) {
        console.error("Failed to load beneficiary types", error);
    }
}

const hasFarmerType = computed(() =>
    beneficiarytypes.value?.some(b => b.beneficiary_type === 'No. of Farmers')
)

const hasSdd = computed(() =>
    beneficiarytypes.value?.some(b => b.beneficiary_type === 'No. of Farmers' || b.beneficiary_type === 'Population of the Community')
)

const genderSplitTypes = ['No. of Farmers', 'Population of the Community']

const beneficiarytypesWithFlags = computed(() =>
    beneficiarytypes.value?.map(b => ({
        ...b,
        requiresGenderSplit: genderSplitTypes.includes(b.beneficiary_type)
    }))
)

const pamb = 'pamb_clearance'

const toggleRequirementType = (id, checked) => {
    if (checked) {
        if (!selectedRequirementTypes.value.includes(id)) {
            selectedRequirementTypes.value.push(id)
            console.log(selectedRequirementTypes.value)
        }
    } else {
        selectedRequirementTypes.value = selectedRequirementTypes.value.filter(b => b !== id)
    }
}

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    files.value = Array.from(event.target.files);
}

const removeFile = (index) => {
    files.value.splice(index, 1);
}

const triggerImageInput = () => {
    if (imageInput.value) {
        imageInput.value.click()
    }
}

const handleImageChange = async (event) => {
    const selectedFiles = Array.from(event.target.files)

    for (const file of selectedFiles) {
        const preview = URL.createObjectURL(file)

        let lat = ''
        let long = ''

        try {
            const exifData = await exifr.gps(file)
            if (exifData) {
                lat = exifData.latitude
                long = exifData.longitude
            }
        } catch (err) {
            console.error('Exif error:', err.message)
        }
        images.value.push({
            file,
            preview,
            lat,
            long
        })
    }
}

const removeImage = (index) => {
    images.value.splice(index, 1)
}

const triggerMapImageInput = () => {
    if (mapImageInput.value) {
        mapImageInput.value.click()
    }
}

const handleMapImageChange = async (event) => {
    const selectedFiles = Array.from(event.target.files)

    for (const file of selectedFiles) {
        const preview = URL.createObjectURL(file)

        mapImages.value.push({
            file,
            preview
        })
    }
    event.target.value = ''
}

const removeMapImage = (index) => {
    const image = mapImages.value[index]
    if (image?.preview) {
        URL.revokeObjectURL(image.preview)
    }
    mapImages.value.splice(index, 1)
}

const setBeneficiaries = async () => {
    await fetchBeneficiaryTypes(props.project?.project_type_id)
    props.project.validation.beneficiaries.forEach(b => {
        const match = beneficiarytypes.value.find(bt => bt.beneficiary_type === b.beneficiary_type)
        if (match) {
            beneficiaryInputs.value[match.id] = b.beneficiary
            if (b.beneficiary_type === 'No. of Farmers' || b.beneficiary_type === 'Population of the Community') {
                validationForm.number_of_females = b?.beneficiary_sdd[0]?.female || ''
                validationForm.number_of_males = b?.beneficiary_sdd[0]?.male || ''
            }
        }
    })
}

watch(
    () => validationForm.project_type_id,
    (project_type_id) => {
        if (project_type_id) {
            fetchBeneficiaryTypes(project_type_id)
        }
    },
    { deep: true }
)

watch(
    () => props.modelValue,
    (isOpen) => {
        if (isOpen && props.project) {
            validationForm.title = props.project?.title
            validationForm.cost = props.project?.cost
            validationForm.project_type_id = props.project?.project_type_id
            const assigned = props.project?.validation?.data?.date_assigned
            validationForm.date_assigned = assigned ? assigned.split(' ')[0] : ''
            setBeneficiaries()
            // if (props.mode === 'edit') {
            const newVal = props.project
            validationForm.present_during_validation = newVal?.validation?.data?.present_during_validation
            validationForm.actions_recommendations = newVal?.validation?.data?.actions_recommendations
            validationForm.remarks = newVal?.validation?.data?.remarks

            if (newVal.validation.data) {
                const assigned = newVal.validation.data.date_assigned
                const validated = newVal.validation.data.date_validated
                const report = newVal.validation.data.date_validation_report

                validationForm.date_assigned = assigned ? assigned.split(' ')[0] : ''
                validationForm.date_validated = validated ? validated.split(' ')[0] : ''
                validationForm.date_validation_report = report ? report.split(' ')[0] : ''
            }

            if (newVal.validation.responsible_people) {
                validationForm.responsible_persons = newVal.validation.responsible_people.map(person => person.employee_id)
            }

            if (newVal.other_requirements) {
                selectedRequirementTypes.value = newVal.other_requirements.map(r => r.requirement_type)
                console.log("reqts", selectedRequirementTypes.value)
            }

            // if (selectedRequirementTypes.value.includes("pamb_clearance")) {
            //     console.log("within pamb")
            // }
            fetchProjectTypes()

            // }
        }

    },
    { deep: true, immediate: true }
)

watch(
    selectedRequirementTypes,
    (reqs) => {
        if (!reqs.includes('pamb_clearance')) {
            selectedPambType.value = ''
            return
        }

        const pambReq = props?.project?.other_requirements?.find(
            r => r.requirement_type === 'pamb_clearance'
        )

        if (!pambReq) return

        console.log("pamb", pambReq.pamb_type_id)
        selectedPambType.value = pambReq.pamb_type_id


    },
    { immediate: true }
)


const handleSubmit = async () => {
    await submitValidation({
        mode: props.mode,
        validationId: props.project?.validation?.data?.id,
        projectId: props.project?.id,
        pamb: pamb,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            resetForm()
        }
    })
}

onMounted(async () => {
    fetchProjectTypes()
    fetchEmployees()
    fetchPambTypes()
})
</script>

<style lang="scss" scoped></style>