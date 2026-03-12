<template>
    <Dialog v-model:open="modelValue">
        <DialogContent class="max-w-4xl w-full max-h-[80vh] overflow-auto bg-dialogbg border border-borderblackui text-primarytext">
            <DialogHeader>
                <DialogTitle>Manage Other Requirements</DialogTitle>
            </DialogHeader>

            <form @submit.prevent="handleSubmit">

                <div class="flex flex-wrap gap-4 mt-2 mb-6">
                    <div v-for="type in requirementTypes" :key="type.id" class="flex items-center space-x-2">
                        <Checkbox :id="type.id" :checked="selectedRequirementTypes.includes(type.id)"
                            @update:checked="toggleRequirementType(type.id, $event)" />
                        <label :for="type.id" class="text-sm">
                            {{ type.label }}
                        </label>
                    </div>
                </div>


                <div v-for="(item, idx) in updateOtherRequirementsForm" :key="idx" class="border p-4 rounded mb-4">
                    <h3 class="font-bold mb-3">
                        {{ toTitle(item.requirement_type) }}
                    </h3>

                    <div class="flex flex-row gap-2 flex-wrap mb-4">
                        <div v-if="item.requirement_type === 'pamb_clearance'">
                            <FormLabel>PAMB</FormLabel>
                            <FormCombobox :options="ecpambtypes" placeholder="Select PAMB"
                                v-model="item.pamb_type_id" />
                        </div>

                        <FormGroup placeholder="Date of Application" v-model="item.date_applied"
                            :errorMessage="errorBag.date_applied" name="date_applied" label="Date of Application"
                            type="date" />

                        <FormGroup placeholder="Date of Issue" v-model="item.date_issued"
                            :errorMessage="errorBag.date_issued" name="date_issued" label="Date of Issue" type="date" />

                        <div>
                            <FormLabel>Employee In-Charge</FormLabel>
                            <FormCombobox :options="allEmployees" placeholder="Select Employee"
                                v-model="item.employee_id" />
                        </div>

                        <div>
                            <FormLabel>Status</FormLabel>
                            <FormCombobox :options="statusOptions" placeholder="Select Status" v-model="item.status" />
                        </div>

                        <div class="flex-1">
                            <FormLabel>Remarks</FormLabel>
                            <Textarea placeholder="Remarks" v-model="item.remarks" rows="1"
                                class="min-h-[42px] h-[42px] resize-none w-full mt-2" />
                        </div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class="flex items-center gap-4">
                            <div>
                                <FormLabel>Upload File</FormLabel>
                                <input type="file" multiple class="hidden" :ref="el => fileInputs[idx] = el"
                                    @change="handleFileChange($event, idx)" />
                                <Button variant="newsecondary" type="button" @click="triggerFileInput(idx)">
                                    Choose Files
                                </Button>
                            </div>

                            <div v-if="item.files && item.files.length" class="flex flex-wrap mt-4">
                                <span v-for="(file, fileIndex) in item.files" :key="fileIndex" :title="file.name"
                                    class="inline-flex items-center gap-1 
                                    bg-info text-white px-2 py-0.5 rounded-full text-xs mr-2 h-6 max-w-[200px] truncate">
                                    {{ file.name }}
                                    <button @click.prevent="removeFile(idx, fileIndex)"
                                        class="text-white hover:text-gray-200">
                                        &times;
                                    </button>
                                </span>

                            </div>
                        </div>
                        <div class="px-2">
                            <div v-if="otherRequirements
                                ?.find(r => r.requirement_type === item.requirement_type)
                                ?.documents?.length">

                                <p class="text-sm text-muted-foreground">Existing Files</p>

                                <div v-for="file in otherRequirements
                                    .find(r => r.requirement_type === item.requirement_type)
                                    .documents" :key="file.file_path">

                                    <p class="text-sm font-medium">
                                        <a :href="file.file_path" target="_blank" class="text-blue-600 underline">
                                            {{ file.file_path.split('/').pop() }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row justify-end mt-4 text-textprimary">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                        <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>

        </DialogContent>
    </Dialog>
</template>

<script setup>

const { errorBag, resetErrorBag } = useCustomError()
const { updateOtherRequirementsForm, initRequirementsForm, submitUpdateOtherRequirements } = useEnvironmentalClearances()
const { employees, fetchEmployees, allEmployees, fetchAllEmployees } = useEmployees()
const { requirementTypes, selectedRequirementTypes } = useValidations()
const { api } = useAxios()

const props = defineProps({
    project: Object,
    modelValue: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['submitted', 'update:modelValue'])

const modelValue = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
})

const otherRequirements = computed(() => props.project?.project_other_requirements || [])
const deletedRequirementIds = ref([])

const statusOptions = [
    { id: 'Ongoing', name: 'Ongoing' },
    { id: 'Approved', name: 'Approved' },
    { id: 'Denied', name: 'Denied' }
]

const ecpambtypes = ref([])

const fetchPambTypes = () => {
    api.get('/api/e_c_pamb_types').then(({ data }) => {
        ecpambtypes.value = data
    })
}

const toTitle = (str) =>
    str.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())

const fileInputs = ref([])

const triggerFileInput = (index) => {
    fileInputs.value[index]?.click()
}

const handleFileChange = (event, rowIndex) => {
    const selectedFiles = Array.from(event.target.files)

    const row = updateOtherRequirementsForm.value[rowIndex]

    if (!row.files) {
        row.files = []
    }

    row.files.push(...selectedFiles)

    console.log('Row index:', rowIndex)
    console.log('Row files array:', row.files)
    console.log('First file object:', row.files[0])
    console.log('Is File instance?', row.files[0] instanceof File)

    // allow re-selecting same file
    event.target.value = null
}


const removeFile = (rowIndex, fileIndex) => {
    const row = updateOtherRequirementsForm.value[rowIndex]
    if (row && row.files) {
        row.files.splice(fileIndex, 1)
    }
}

const isSubmitting = ref(false)

const handleSubmit = async () => {
    isSubmitting.value = true
    resetErrorBag()
    await submitUpdateOtherRequirements({
        validationId: props?.project?.validation?.data?.id,
        projectId: props?.project?.id,
        deletedRequirementIds: deletedRequirementIds.value,
        onSuccess: () => {
            deletedRequirementIds.value = []
            emit('submitted')
            emit('update:modelValue', false)
            isSubmitting.value = false
        }
    })
}

function toggleRequirementType(type, checked) {
    if (checked) {
        if (!selectedRequirementTypes.value.includes(type)) {
            selectedRequirementTypes.value.push(type)
        }

        const existingRequirement = otherRequirements.value
            .find(r => r.requirement_type === type)

        if (existingRequirement?.id) {
            deletedRequirementIds.value = deletedRequirementIds.value
                .filter(id => id !== existingRequirement.id)
        }

        const exists = updateOtherRequirementsForm.value
            .find(r => r.requirement_type === type)

        if (!exists) {
            updateOtherRequirementsForm.value.push({
                id: existingRequirement?.id || null,
                requirement_type: type,
                pamb_type_id: existingRequirement?.pamb_type_id || '',
                date_applied: existingRequirement?.date_applied || '',
                date_issued: existingRequirement?.date_issued || '',
                employee_id: existingRequirement?.employee_id || '',
                status: existingRequirement?.status || '',
                remarks: existingRequirement?.remarks || '',
                files: [],
            })
        }

    } else {
        const requirementToRemove = updateOtherRequirementsForm.value
            .find(r => r.requirement_type === type)

        if (requirementToRemove?.id && !deletedRequirementIds.value.includes(requirementToRemove.id)) {
            deletedRequirementIds.value.push(requirementToRemove.id)
        }

        selectedRequirementTypes.value = selectedRequirementTypes.value.filter(t => t !== type)

        updateOtherRequirementsForm.value =
            updateOtherRequirementsForm.value.filter(r => r.requirement_type !== type)
    }
}


watch(() => updateOtherRequirementsForm.value, (val) => {
    selectedRequirementTypes.value = val.map(r => r.requirement_type)
}, { immediate: true })

onMounted(() => {
    fetchPambTypes()
    fetchEmployees()
    fetchAllEmployees()

    selectedRequirementTypes.value = otherRequirements.value.map(
        req => req.requirement_type
    )

    initRequirementsForm(otherRequirements.value, selectedRequirementTypes.value)

    console.log('Other Reqs Files', otherRequirements.value?.documents)
})
</script>

<style lang="scss" scoped></style>
