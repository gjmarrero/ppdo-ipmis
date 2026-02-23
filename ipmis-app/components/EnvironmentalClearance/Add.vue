<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c =>
                        c.toUpperCase())
                        }} ECC data</h2>
                </div>
            </template>
            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow gap-2 mb-8">
                    <div>
                        <FormLabel>Certificate Type</FormLabel>
                        <FormCombobox :options="eccertificatetypes" placeholder="Select certificate type"
                            v-model="environmentalClearanceForm.e_c_certificate_type_id" />
                    </div>
                    <div>
                        <FormLabel>Status</FormLabel>
                        <FormCombobox :options="statusOptions" placeholder="Select status"
                            v-model="environmentalClearanceForm.status" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow mb-8">
                    <FormGroup placeholder="Application Date" v-model="environmentalClearanceForm.date_application"
                        :errorMessage="errorBag.date_application" name="date_application" label="Date of Application"
                        type="date" />
                    <FormGroup placeholder="Date Issued" v-model="environmentalClearanceForm.date_issued"
                        :errorMessage="errorBag.date_issued" name="date_issued" label="Date Issued" type="date" />
                </div>
                <Separator orientation="horizontal" class="bg-gray-300 my-2" />
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                    <div>
                        <FormLabel>Employee In-Charge</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select Employee"
                            v-model="environmentalClearanceForm.employee_id" />
                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow mb-8">
                    <div>
                        <FormLabel>Transmitted to PEO</FormLabel>
                        <Switch v-model="isTransmitted" />
                    </div>
                    <FormGroup placeholder="Date Transmitted to PEO"
                        v-model="environmentalClearanceForm.date_transmitted" :errorMessage="errorBag.date_transmitted"
                        name="date_transmitted" label="Date Transmitted to PEO" type="date" />
                </div>
                <Separator orientation="horizontal" class="bg-gray-200 my-2" />
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                    <FormLabel>Remarks</FormLabel>
                    <Textarea placeholder="Remarks" v-model="environmentalClearanceForm.remarks" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8">
                    <div>
                        <FormLabel>ECC/CNC</FormLabel>
                        <div class="pb-2">
                            <input type="file" id="fileUpload" multiple class="hidden" ref="fileInput"
                                @change="handleFileChange" />
                            <Button variant="secondary" type="button" @click="triggerFileInput"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Choose Files
                            </Button>
                        </div>
                        <div v-if="files.length" class="flex flex-wrap mt-0">
                            <span v-for="(file, index) in files" :key="index"
                                class="bg-info text-white px-3 py-1 rounded-full text-sm mr-2 mb-2">
                                {{ file.name }}
                                <button @click.prevent="removeFile(index)" class="ml-1 text-xs text-white">
                                    &times;
                                </button>
                            </span>
                        </div>
                    </div>
                    <div v-if="enviClearanceFiles">
                        <span v-for="exisiting_file in enviClearanceFiles">
                            <span v-if="exisiting_file.type === 'ecc_cnc'">
                                <FormLabel>Existing ECC/CNC</FormLabel>
                                <a :href="exisiting_file.file_path" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 underline">
                                    {{ exisiting_file.file_path.split('/').pop() }}
                                </a>
                            </span>

                        </span>
                    </div>
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
import Spinner from '../Spinner.vue'
import Switch from '../ui/switch/Switch.vue'

const { api } = useAxios()
const { environmentalClearanceForm, files, fileInput, isTransmitted, submitEnvironmentalClearance, resetForm, isSubmitting } = useEnvironmentalClearances()
const { employees, fetchEmployees } = useEmployees()
const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

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

const triggerFileInput = () => {
    fileInput.value.click()
}

const handleFileChange = (event) => {
    files.value = Array.from(event.target.files);
}

const removeFile = (index) => {
    files.value.splice(index, 1)
}

const enviClearanceFiles = computed(() => props.environmental_clearance?.files)

watch(
    () => [props.modelValue, props.environmental_clearance],
    ([isOpen, enviClearance]) => {
        if (isOpen && props.mode === 'edit' && enviClearance) {
            const data = Array.isArray(enviClearance) ? enviClearance[0] : enviClearance

            environmentalClearanceForm.e_c_certificate_type_id = data.certificate_type_id || ''
            environmentalClearanceForm.date_application = data.date_application ? data.date_application.split(' ')[0] : ''
            environmentalClearanceForm.date_issued = data.date_issued ? data.date_issued.split(' ')[0] : ''
            environmentalClearanceForm.employee_id = data.employee || ''
            environmentalClearanceForm.status = data.status || ''
            environmentalClearanceForm.is_transmitted_peo = data.is_transmitted_peo || ''
            environmentalClearanceForm.date_transmitted = data.date_transmitted ? data.date_transmitted.split(' ')[0] : ''
            environmentalClearanceForm.remarks = data.remarks || ''
        }
    },
    { immediate: true }
)

const handleSubmit = async () => {
    await submitEnvironmentalClearance({
        mode: props.mode,
        environmentalClearanceId: props.funded_project?.latest_funding?.latest_environmental_clearance?.id ?? null,
        fundedProjectId: props.funded_project?.latest_funding?.id,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            resetForm()
        }
    })
}

const eccertificatetypes = ref([])

const fetchECCertificateTypes = () => {
    api.get('/api/e_c_certificate_types').then(({ data }) => {
        eccertificatetypes.value = data
    })
}

const statusOptions = [
    { id: 'Ongoing', name: 'Ongoing' },
    { id: 'Approved', name: 'Approved' },
    { id: 'Denied', name: 'Denied' }
]

onMounted(() => {
    fetchECCertificateTypes()
    fetchEmployees()
    console.log("current project", props.environmental_clearance)
})

</script>

<style lang="scss" scoped></style>