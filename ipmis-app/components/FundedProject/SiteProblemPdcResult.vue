<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Add PDC Result</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <AlertNotice variant="info" title="Note">
                        Reported site problem: {{ funded_project?.latest_funding?.latest_site_problem?.problem_type }}
                    </AlertNotice>
                </div>
                <div class="mb-4">
                    <FormLabel>PDC Result</FormLabel>
                    <FormCombobox :options="pdcResultOptions" placeholder="Select result"
                        v-model="pdcResultForm.pdc_result" :errorMessage="errorBag.pdc_result" />
                </div>
                <!-- <FormGroup class="mb-4" placeholder="PDC Resolution Number"
                    v-model="pdcResultForm.pdc_resolution_number" :errorMessage="errorBag.pdc_resolution_number"
                    name="pdc_resolution_number" label="PDC Resolution Number" type="text" />

                <FormGroup class="mb-4" placeholder="B.O. Resolution Number"
                    v-model="pdcResultForm.sb_resolution_number" :errorMessage="errorBag.sb_resolution_number"
                    name="sb_resolution_number" label="B.O. Resolution Number" type="text" /> -->

                <div class="mb-4">
                    <FormLabel>Supplemental Budget Number</FormLabel>
                    <FormCombobox :options="supplementalBudgets" placeholder="Select Supplemental Budget"
                        v-model="pdcResultForm.archive_id" :errorMessage="errorBag.archive_id" />
                </div>
                <div v-if="funded_project?.latest_site_problem?.problem_type === 'Change of Project Title'">
                    <FormGroup class="mb-4" placeholder="New Project Title" v-model="pdcResultForm.new_project_title"
                        :errorMessage="errorBag.new_project_title" name="new_project_title" label="New Project Title"
                        type="text" />
                </div>

                <div v-if="funded_project?.latest_site_problem?.problem_type === 'Additional Funding'">
                    <div class="mb-4">
                        <FormLabel>Fundsource</FormLabel>
                        <FormCombobox :options="fundSources" placeholder="Select fundsource"
                            v-model="pdcResultForm.fundsource_id" :errorMessage="errorBag.fundsource_id" />
                    </div>

                    <CleaveInput v-model="pdcResultForm.additional_fund" label="Additional Fund"
                        labelFor="additional_fund" name="additional_fund" placeholder="Additional Fund"
                        :errorMessage="errorBag.additional_fund"
                        :options="{ numeral: true, numeralThousandsGroupStyle: 'thousand', numeralDecimalScale: 2 }"
                        class="mb-4" />

                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                    <FormLabel>Remarks</FormLabel>
                    <Textarea placeholder="Remarks" v-model="pdcResultForm.remarks" />
                </div>

                <!-- <div class="flex flex-col gap-4 mb-8">
                    <FormLabel>PDC Resolution</FormLabel>
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6">

                        <div>
                            <input type="file" multiple class="hidden" ref="pdcFileInput"
                                @change="handlePdcFileChange" />
                            <Button variant="secondary" type="button" @click="triggerPdcFileInput"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Choose Files
                            </Button>
                        </div>
                    </div>

                    <div v-if="pdcFiles.length" class="flex flex-wrap mt-2">
                        <span v-for="(file, index) in pdcFiles" :key="index"
                            class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2 flex items-center">
                            {{ file.name }}
                            <button @click.prevent="removePdcFile(index)"
                                class="ml-2 text-xs text-white hover:text-gray-200">
                                &times;
                            </button>
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-4 mb-8">
                    <FormLabel>SB Resolution</FormLabel>
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6">

                        <div>
                            <input type="file" multiple class="hidden" ref="sbResFileInput"
                                @change="handleSbResFileChange($event)" />
                            <Button variant="secondary" type="button" @click="triggerSbResFileInput"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Choose Files
                            </Button>
                        </div>
                    </div>

                    <div v-if="sbResFiles.length" class="flex flex-wrap mt-2">
                        <span v-for="(file, index) in sbResFiles" :key="index"
                            class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2 flex items-center">
                            {{ file.name }}
                            <button @click.prevent="removeSbResFile(index)"
                                class="ml-2 text-xs text-white hover:text-gray-200">
                                &times;
                            </button>
                        </span>
                    </div>
                </div> -->

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
import CleaveInput from '../Form/CleaveInput.vue'
const { pdcResultForm, pdcFiles, pdcFileInput, sbResFiles, sbResFileInput, submitPdcResult } = useFundedProjects()
const { fundSources, fetchFundSources } = useFundSources()
const { supplementalBudgets, fetchArchivedSupplementalBudgets } = useArchives()
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

const triggerPdcFileInput = () => {
    pdcFileInput.value.click();
}

const handlePdcFileChange = (event) => {
    pdcFiles.value = Array.from(event.target.files);
}

const removePdcFile = (index) => {
    pdcFiles.value.splice(index, 1);
}

const triggerSbResFileInput = () => {
    sbResFileInput.value.click();
}

const handleSbResFileChange = (event) => {
    sbResFiles.value = Array.from(event.target.files);
}

const removeSbResFile = (index) => {
    sbResFiles.value.splice(index, 1);
}

const pdcResultOptions = [
    { id: 'Resolved', name: 'Resolved' },
    { id: 'Disapproved', name: 'Disapproved' },
]

const isSubmitting = ref(false)

const handleSubmit = async () => {
    isSubmitting.value = true    
    await submitPdcResult({
        siteProblemId: props.funded_project.latest_funding?.latest_site_problem.id,
        onSuccess: () => {
            emit('form-submitted')
            isSubmitting.value = false
        }
    })
}

onMounted(() => {
    fetchFundSources()
    fetchArchivedSupplementalBudgets()
    console.log(props.funded_project)
})

</script>