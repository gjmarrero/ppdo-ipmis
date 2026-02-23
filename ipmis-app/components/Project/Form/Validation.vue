<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow mb-8">
        <div>
            <FormGroup placeholder="Date assigned" v-model="validationForm.date_assigned"
                :errorMessage="errorBag.date_assigned" name="date_assigned" label="Date Assigned" type="date" />
        </div>

        <FormGroup placeholder="Date validated" v-model="validationForm.date_validated"
            :errorMessage="errorBag.date_validated" name="date_validated" label="Date Validated" type="date" />
        <FormGroup placeholder="Date of validation report" v-model="validationForm.date_validation_report"
            :errorMessage="errorBag.date_validation_report" name="date_validation_report"
            label="Date of Validation Report" type="date" />
    </div>
    <div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-grow">
            <div class="mb-6">
                <FormLabel>Actions or Recommendations</FormLabel>
                <Textarea placeholder="Actions or recommendations" v-model="validationForm.actions_recommendations" />
            </div>

            <div class="mb-6">
                <FormLabel>Beneficiaries</FormLabel>
                <Textarea placeholder="Beneficiaries" v-model="validationForm.beneficiaries" />
            </div>

            <div class="mb-6">
                <FormLabel>Present During Validation</FormLabel>
                <Textarea placeholder="Present during validation" v-model="validationForm.present_during_validation" />
            </div>

            <div class="mb-6">
                <FormLabel>Remarks</FormLabel>
                <Textarea placeholder="Remarks" v-model="validationForm.remarks" />
            </div>

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
        <div>
            <FormLabel>Responsible persons</FormLabel>
            <FormMultiSelectCombobox :options="employees" placeholder="Select employees"
                v-model="validationForm.responsible_persons" />
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    project: Object
})

const { api } = useAxios()

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const validationForm = reactive({
    date_assigned: '',
    date_validated: '',
    date_validation_report: '',
    actions_recommendations: '',
    beneficiaries: '',
    present_during_validation: '',
    remarks: '',
    responsible_persons: [
        { employee_id: '' }
    ]
})

const emit = defineEmits(['add'])

function addValidation() {
    resetErrorBag()
    api.post('/api/validation', validationForm).then(({ data }) => {
        isDrawerOpen.value = false
        emit('add')
    }).catch(err => {
        transformValidationErrors(err.response)
    })
}

const employees = ref([])

function fetchEmployees() {
    api.get('/api/fetchEmployees').then(({ data }) => {
        employees.value = data
    })
}

fetchEmployees()

onMounted(()=>{
    console.log("Validate:",props.project)
})
</script>

<style lang="scss" scoped></style>