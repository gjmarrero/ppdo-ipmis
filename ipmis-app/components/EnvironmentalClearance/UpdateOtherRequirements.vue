<template>
    <Dialog v-model:open="modelValue">
        <DialogContent class="max-w-4xl w-full">
            <DialogHeader>
                <DialogTitle>Update Other Requirements</DialogTitle>
            </DialogHeader>
            <!-- <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                    <FormGroup placeholder="Date of Application" v-model="updateOtherRequirementsForm.date_applied" :errorMessage="errorBag.date_applied"
                        name="date_applied" label="Date of Application" type="date" />

                    <FormGroup placeholder="Date of Issue" v-model="updateOtherRequirementsForm.date_issued" :errorMessage="errorBag.date_issued"
                        name="date_issued" label="Date of Issue" type="date" />
                    <div>
                        <FormLabel>Employee In-Charge</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select Employee"
                            v-model="updateOtherRequirementsForm.employee_id" />
                    </div>
                    <div>
                        <FormLabel>Status</FormLabel>
                        <FormCombobox :options="statusOptions" placeholder="Select Status"
                            v-model="updateOtherRequirementsForm.status" />
                    </div>
                    <div>
                        <FormLabel>Remarks</FormLabel>
                        <Textarea placeholder="Remarks" v-model="updateOtherRequirementsForm.remarks" />
                    </div>
                </div>
                <div class="mt-10">
                    <Button type="submit" variant="primary">Submit</Button>
                </div>
            </form> -->
            <form @submit.prevent="handleSubmit">
                <div class="flex flex-wrap gap-4 mt-2">
                    <div v-for="type in requirementTypes" :key="type.id" class="flex items-center space-x-2">
                        <Checkbox :id="type.id" :checked="selectedRequirementTypes.includes(type.id)"
                            @update:checked="toggleRequirementType(type.id, $event)" />
                        <label :for="type.id" class="text-sm text-gray-700">
                            {{ type.label }}
                        </label>
                    </div>
                </div>
                <div v-for="(item, idx) in updateOtherRequirementsForm" :key="item.id" class="border p-4 rounded mb-4">
                    <h3 class="font-bold mb-3">{{ toTitle(item.requirement_type) }}</h3>
                    <div class="flex flex-row gap-2">
                        <FormGroup placeholder="Date of Application" v-model="item.date_applied"
                            :errorMessage="errorBag.date_applied" name="date_applied" label="Date of Application"
                            type="date" />
                        <FormGroup placeholder="Date of Issue" v-model="item.date_issued"
                            :errorMessage="errorBag.date_issued" name="date_issued" label="Date of Issue" type="date" />
                        <div>
                            <FormLabel>Employee In-Charge</FormLabel>
                            <FormCombobox :options="employees" placeholder="Select Employee"
                                v-model="item.employee_id" />
                        </div>
                        <div>
                            <FormLabel>Status</FormLabel>
                            <FormCombobox :options="statusOptions" placeholder="Select Status" v-model="item.status" />
                        </div>
                        <div>
                            <FormLabel>Remarks</FormLabel>
                            <Textarea placeholder="Remarks" v-model="item.remarks" rows="1"
                                class="min-h-[42px] h-[42px] resize-none w-full mt-2" />
                        </div>
                    </div>


                </div>
                <div class="flex justify-end mt-10">
                    <Button type="submit" variant="primary">Submit</Button>
                </div>
            </form>

        </DialogContent>
        <DialogFooter>

        </DialogFooter>
    </Dialog>
</template>

<script setup>
import Button from '../Button.vue'

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const { updateOtherRequirementsForm, initRequirementsForm, submitUpdateOtherRequirements } = useEnvironmentalClearances()

const { employees, fetchEmployees } = useEmployees()

const {requirementTypes, selectedRequirementTypes} = useValidations()

const props = defineProps({
    project: Object,
    modelValue: {
        type: Boolean,
        default: false
    }
})

const modelValue = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
})

const emit = defineEmits(['submitted', 'update:modelValue'])

const otherRequirements = computed(() => props.project?.validation?.other_requirements || [])

const statusOptions = [
    { id: 'Ongoing', name: 'Ongoing' },
    { id: 'Approved', name: 'Approved' },
    { id: 'Denied', name: 'Denied' }
]

const toTitle = (str) =>
    str.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())

const handleSubmit = async () => {
    await submitUpdateOtherRequirements({
        onSuccess: () => {
            emit('form-submitted')
            emit('update:modelValue', false)
        }
    })
}

const toggleRequirementType = (id, checked) => {
    if (checked) {
        if (!selectedRequirementTypes.value.includes(id)) {
            selectedRequirementTypes.value.push(id)
        }
    } else {
        selectedRequirementTypes.value = selectedRequirementTypes.value.filter(b => b !== id)
    }
}

onMounted(() => {
    console.log("Mounted", props.project)
    console.log("Computed other req", otherRequirements.value)
    fetchEmployees()
    initRequirementsForm(otherRequirements.value)
    console.log("Other reqs", updateOtherRequirementsForm)
})

</script>

<style lang="scss" scoped></style>