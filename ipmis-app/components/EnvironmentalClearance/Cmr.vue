<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Update CMR</h2>
                </div>
            </template>
            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow mb-8">
                    <FormGroup placeholder="Date Prepared" v-model="cmrForm.date_prepared"
                        :errorMessage="errorBag.date_prepared" name="date_prepared" label="CMR Date Prepared"
                        type="date" />
                    <FormGroup placeholder="Date Submitted" v-model="cmrForm.date_submitted"
                        :errorMessage="errorBag.date_submitted" name="date_submitted" label="CMR Date Submitted"
                        type="date" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                    <FormLabel>Remarks</FormLabel>
                    <Textarea placeholder="Remarks" v-model="cmrForm.remarks" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8">
                    <div>
                        <FormLabel>CMR File</FormLabel>
                        <div class="pb-2">
                            <input type="file" id="cmrFileUpload" multiple class="hidden" ref="cmrFileInput"
                                @change="handleCmrFileChange" />
                            <Button variant="secondary" type="button" @click="triggerCmrFileInput"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Choose Files
                            </Button>
                        </div>
                        <div v-if="cmr_files.length" class="flex flex-wrap mt-0">
                            <span v-for="(file, index) in cmr_files" :key="index"
                                class="bg-info text-white px-3 py-1 rounded-full text-sm mr-2 mb-2">
                                {{ file.name }}
                                <button @click.prevent="removeCmrFile(index)" class="ml-1 text-xs text-white">
                                    &times;
                                </button>
                            </span>
                        </div>
                    </div>

                    <div v-if="enviClearanceFiles">
                        <span v-for="exisiting_file in enviClearanceFiles">
                            <span v-if="exisiting_file.type === 'cmr'">
                                <FormLabel>Existing CMR</FormLabel>
                                <a :href="exisiting_file.file_path" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 underline">
                                    {{ exisiting_file.file_path.split('/').pop() }}
                                </a>
                            </span>

                        </span>
                    </div>
                </div>

                <div class="flex flex-row justify-end">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmittingCmr">
                        <Spinner v-if="isSubmittingCmr" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>
const { cmrForm, cmr_files, cmrFileInput, submitCmr, isSubmittingCmr } = useEnvironmentalClearances()
const { employees, fetchEmployees } = useEmployees()
const { errorBag } = useCustomError()

const props = defineProps({
    mode: {
        type: String,
        default: 'add'
    },
    environmental_clearance: {
        type: Object,
        default: null
    },
    funded_project: Object,
    modelValue: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['form-submitted'])

const isOpen = computed({
    get: () => props.modelValue,
    set: val => emit('update:modelValue', val)
})

const triggerCmrFileInput = () => {
    cmrFileInput.value.click()
}

const handleCmrFileChange = (event) => {
    cmr_files.value = Array.from(event.target.files)
}

const removeCmrFile = (index) => {
    cmr_files.value.splice(index, 1)
}

const enviClearanceFiles = computed(() => props.environmental_clearance?.files)

watch(
    () => [props.modelValue, props.environmental_clearance],
    ([isOpen, enviClearance]) => {
        if (isOpen && props.mode === 'edit' && enviClearance) {
            const data = Array.isArray(enviClearance) ? enviClearance[0] : enviClearance

            cmrForm.date_prepared = data.date_prepared ? data.date_prepared.split(' ')[0] : ''
            cmrForm.date_submitted = data.date_submitted ? data.date_submitted.split(' ')[0] : ''
            cmrForm.remarks = data.remarks || ''
        }
    },
    { immediate: true }
)

const handleSubmit = async () => {
    await submitCmr({
        mode: props.mode,
        environmentalClearanceId: props.funded_project?.latest_funding?.latest_environmental_clearance?.id ?? null,
        fundedProjectId: props.funded_project?.latest_funding?.id,
        onSuccess: () => {
            emit('form-submitted', props.mode)
        }
    })
}

onMounted(() => {
    console.log("current project", props.environmental_clearance)
})

</script>

<style lang="scss" scoped></style>