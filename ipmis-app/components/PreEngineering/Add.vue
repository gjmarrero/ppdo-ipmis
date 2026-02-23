<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c =>
                        c.toUpperCase())
                        }} pre-engineering data</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow mb-8">
                    <FormGroup placeholder="Date of Data Gathering" v-model="preEngineeringForm.date_conducted"
                        :errorMessage="errorBag.date_conducted" name="date_conducted" label="Date of Data Gathering"
                        type="date" />
                    <div class="grid grid-cols-1 md:grid-cols-1 flex-grow">
                        <FormLabel>Name of Programmer</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select name of programmer"
                            v-model="preEngineeringForm.employee_id" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8 gap-2">
                    <div>
                        <FormLabel>Project Type</FormLabel>
                        <FormCombobox :options="projectTypes" placeholder="Select project type"
                            v-model="preEngineeringForm.project_type_id" />
                    </div>

                    <CleaveInput v-model="preEngineeringForm.programmed_cost" label="Programmed Cost"
                        labelFor="programmed_cost" name="programmed_cost" placeholder="Programmed Cost"
                        :errorMessage="errorBag.programmed_cost"
                        :options="{ numeral: true, numeralThousandsGroupStyle: 'thousand', numeralDecimalScale: 2 }" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 flex-grow mb-4">
                    <FormLabel>Scope of Work</FormLabel>
                </div>

                <div v-for="(scope, index) in preEngineeringForm.scopes"
                    class="grid grid-cols-1 md:grid-cols-12 gap-2 flex-grow mb-2">
                    <div class="grid grid-cols-1 md:col-span-11 gap-2 flex-grow mb-2">
                        <FormCombobox :options="specificWorks" placeholder="Select specific scope of work"
                            v-model="scope.scope_of_work_id" :name="'scopes[' + index + '][scope_of_work_id]'"
                            :id="'scope_of_work_id_' + index" />
                    </div>
                    <div>
                        <Trash2Icon class="h-5 w-5 text-red-600" @click="removeScope(index)" />
                    </div>
                    <div class="col-span-1 md:col-span-11">
                        <Textarea placeholder="Description" v-model="scope.description"
                            :name="'scopes[' + index + '][description]'" :id="'description_' + index"
                            class="text-black" />
                    </div>
                </div>
                <div class="mb-8">
                    <PlusIcon class="h-5 w-5 text-blue-600" @click="addScope" />
                </div>
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
                                            <FormGroup class="flex items-center gap-5" placeholder="X"
                                                v-model="image.lat" label="Lat" type="text" />
                                            <FormGroup class="flex items-center gap-2" placeholder="Y"
                                                v-model="image.long" label="Long" type="text" />
                                        </div>
                                        <div class="ml-5 max-w-24">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="mode === 'edit'">
                        <span v-if="funded_project?.latest_funding?.latest_preengineering?.images.length > 0">
                            <p class="text-sm text-muted-foreground">Existing Images</p>
                        </span>
                        <span v-for="image in funded_project?.latest_funding?.latest_preengineering?.images">
                            <p class="text-sm font-medium">
                                <a :href="image.image_path" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 underline">
                                    {{ image.image_path.split('/').pop() }}
                                </a>
                            </p>
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 flex-grow mb-8 gap-2">
                    <FormGroup placeholder="Date POW prepared" v-model="preEngineeringForm.date_prepared_pow"
                        :errorMessage="errorBag.date_prepared_pow" name="date_prepared_pow" type="date"
                        label="Date POW prepared" />
                    <FormGroup placeholder="Date POW checked" v-model="preEngineeringForm.date_checked_pow"
                        :errorMessage="errorBag.date_checked_pow" name="date_checked_pow" type="date"
                        label="Date POW checked" />
                    <FormGroup placeholder="Date POW reviewed" v-model="preEngineeringForm.date_reviewed_pow"
                        :errorMessage="errorBag.date_reviewed_pow" name="date_reviewed_pow" type="date"
                        label="Date POW reviewed" />
                </div>

                <div class="flex flex-row justify-end">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                        <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>
import * as exifr from 'exifr'
import CleaveInput from '../Form/CleaveInput.vue'
import { PlusIcon, Trash2Icon } from 'lucide-vue-next'

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()
const { employees, fetchEmployees } = useEmployees()
const { projectTypes, fetchProjectTypes } = useProjectTypes()
const { specificWorks, fetchSpecificWorks } = useSpecificScopeOfWorks()
const { preEngineeringForm, images, imageInput, submitPreEngineering, resetForm, isSubmitting } = usePreEngineerings()

const props = defineProps({
    funded_project: Object,
    pre_engineering: {
        type: Object,
        defaul: null
    },
    modelValue: {
        type: Boolean,
        default: false
    },
    mode: {
        type: String,
        default: 'add'
    }
})

const emit = defineEmits(['form-submitted'])

const isOpen = computed({
    get: () => props.modelValue,
    set: val => emit('update:modelValue', val)
})

const triggerImageInput = () => {
    if (imageInput.value) {
        imageInput.value.click()
    }
}

const addScope = () => {
    preEngineeringForm.scopes.push({ scope_of_work_id: '', description: '', remarks: '' })
}

const removeScope = (index) => {
    preEngineeringForm.scopes.splice(index, 1)
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

const handleSubmit = async () => {
    await submitPreEngineering({
        mode: props.mode,
        preEngineeringId: props.funded_project?.latest_funding?.latest_preengineering?.id ?? null,
        fundedProjectId: props.funded_project?.latest_funding?.id,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            resetForm()
        }
    })
}

watch(
    () => [props.modelValue, props.funded_project?.latest_funding?.latest_preengineering],
    ([isOpen, preEng]) => {
        preEngineeringForm.programmed_cost = Number(props.funded_project?.approved_cost).toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }) || ''
        preEngineeringForm.project_type_id = props.funded_project?.project_type_id
        if (isOpen && props.mode === 'edit' && preEng) {
            const data = Array.isArray(preEng) ? preEng[0] : preEng

            preEngineeringForm.programmed_cost = data.programmed_cost || ''
            preEngineeringForm.employee_id = data.employee_id || ''
            preEngineeringForm.project_type_id = data.project_type_id || ''
            preEngineeringForm.scope_of_work_id = data.scope_of_work_id || ''

            preEngineeringForm.scopes = (data.scopes || []).map(scope => ({
                scope_of_work_id: scope.scope_of_work_id || '',
                description: scope.description || ''
            }))

            if (preEngineeringForm.scopes.length === 0) {
                preEngineeringForm.scopes.push({ scope_of_work_id: '', description: '' })
            }

            preEngineeringForm.date_conducted = data.date_conducted ? data.date_conducted.split(' ')[0] : ''
            preEngineeringForm.date_prepared_pow = data.date_prepared_pow ? data.date_prepared_pow.split(' ')[0] : ''
            preEngineeringForm.date_checked_pow = data.date_checked_pow ? data.date_checked_pow.split(' ')[0] : ''
            preEngineeringForm.date_reviewed_pow = data.date_reviewed_pow ? data.date_reviewed_pow.split(' ')[0] : ''

            preEngineeringForm.date_submitted_divhead = data.date_submitted_divhead ? data.date_submitted_divhead.split(' ')[0] : ''
            preEngineeringForm.date_approved_pe = data.date_approved_pe ? data.date_approved_pe.split(' ')[0] : ''
            preEngineeringForm.date_submitted_lce = data.date_submitted_lce ? data.date_submitted_lce.split(' ')[0] : ''
            preEngineeringForm.date_approved_lce = data.date_approved_lce ? data.date_approved_lce.split(' ')[0] : ''
        }
    },
    { immediate: true }
)


onMounted(async () => {
    await fetchProjectTypes()
    if (props.funded_project?.project?.project_type?.id) {
        const selected = projectTypes.value.find(pt => pt.id === props.funded_project?.project?.project_type?.id)
        if (selected) {
            preEngineeringForm.project_type_id = selected
        }
    }
    fetchSpecificWorks()
    fetchEmployees()
    console.log("Mounted preengform", props.funded_project?.latest_funding?.latest_preengineering)
})

</script>

<style lang="scss" scoped></style>