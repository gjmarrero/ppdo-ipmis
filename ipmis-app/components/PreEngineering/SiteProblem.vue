<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Add Site Problem</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <FormLabel>Site Problem</FormLabel>
                    <FormCombobox :options="problemTypeOptions" placeholder="Select problem type"
                        v-model="siteProblemForm.problem_type" :errorMessage="errorBag.problem_type" />
                </div>
                <FormGroup v-if="showOtherSiteProblemInput" placeholder="Please Specify"
                    v-model="siteProblemForm.other_site_problem" :errorMessage="errorBag.other_site_problem"
                    name="other_site_problem" label="Other Site Problem " type="text" class="mb-4" />
                <FormGroup placeholder="Date of Report Preparation" v-model="siteProblemForm.date_report_prepared"
                    :errorMessage="errorBag.date_report_prepared" name="date_report" label="Date of Report Preparation"
                    type="date" class="mb-4" />
                <FormGroup placeholder="Date Report Checked" v-model="siteProblemForm.date_report_checked"
                    :errorMessage="errorBag.date_report_checked" name="date_report_checked" label="Date Report Checked"
                    type="date" class="mb-4" />
                <FormGroup placeholder="Date Report Submitted to LCE" v-model="siteProblemForm.date_report_submitted"
                    :errorMessage="errorBag.date_report_submitted" name="date_report_submitted"
                    label="Date Report Submitted to LCE" type="date" class="mb-4" />
                <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                    <FormLabel>Remarks</FormLabel>
                    <Textarea placeholder="Remarks" v-model="siteProblemForm.remarks" />
                </div>
                <div class="flex flex-col gap-4 mb-8">
                    <FormLabel>Report</FormLabel>
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
                            <button @click.prevent="removeFile(index)"
                                class="ml-2 text-xs text-white hover:text-gray-200">
                                &times;
                            </button>
                        </span>
                    </div>
                </div>

                <div class="flex flex-row justify-end">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmittingSiteProblem">
                        <Spinner v-if="isSubmittingSiteProblem" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>
const { siteProblemForm, files, fileInput, submitSiteProblem, resetSiteProblemForm, isSubmittingSiteProblem } = usePreEngineerings()
const { errorBag } = useCustomError()
const props = defineProps({
    funded_project: Object,
    modelValue: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['update:modelValue', 'form-submitted'])

const isOpen = computed({
    get: () => props.modelValue,
    set: val => emit('update:modelValue', val)
})

const problemTypeOptions = [
    { id: 'Additional Funding', name: 'Additional Funding' },
    { id: 'Change of Project Title', name: 'Change of Project Title' },
    { id: 'Realignment', name: 'Realignment' },
    { id: 'Awaiting Completion of Ongoing Project', name: 'Awaiting Completion of Ongoing Project' },
    { id: 'Road Right of Way Problem', name: 'Road Right of Way Problem' },
    { id: 'Others', name: 'Others' }
]

const triggerFileInput = () => {
    fileInput.value.click()
};

const handleFileChange = (event) => {
    files.value = Array.from(event.target.files)
};

const removeFile = (index) => {
    files.value.splice(index, 1)
}

const handleSubmit = async () => {
    await submitSiteProblem({
        fundedProjectId: props.funded_project.latest_funding.id,
        onSuccess: () => {
            emit('form-submitted')
            resetSiteProblemForm()
        }
    })
}

const showOtherSiteProblemInput = ref(false)

watch(
    () => siteProblemForm.problem_type,
    (newValue) => {
        showOtherSiteProblemInput.value =
            newValue === 'Others' || newValue?.value === 'Others'

        if (!showOtherSiteProblemInput.value) {
            siteProblemForm.other_site_problem = ''
        }
    }
)
</script>