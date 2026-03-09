<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">{{ mode }} implementation info for project {{
                        props.funded_project?.project?.id }}</h2>
                </div>
            </template>
            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-2 md:grid-cols-1 flex-grow">
                    <div class="mb-4">
                        <FormGroup placeholder="Date Documents Received" v-model="implementationForm.date_received"
                            :errorMessage="errorBag.date_received" name="date_received" label="Date Documents Received"
                            type="date" />
                    </div>
                    <div class="mb-4">
                        <FormLabel>Project Engineer</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select Employee"
                            v-model="implementationForm.employee_id" />
                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                    <FormGroup placeholder="Date Start" v-model="implementationForm.date_start"
                        :errorMessage="errorBag.date_start" name="date_start" label="Date Start" type="date" />
                </div>
                <div class="flex flex-row mt-10 justify-end">
                    <Button type="submit" variant="newprimary">Submit Data</Button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>

const { implementationForm, resetForm, submitImplementation } = useImplementations()
const { errorBag } = useCustomError()
const { employees, fetchEmployees } = useEmployees()

const props = defineProps({
    funded_project: Object,
    mode: {
        type: String,
        default: 'add'
    },
    modelValue: {
        type: Boolean,
        default: false
    },
    implementation: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['form-submitted'])

const isOpen = computed({
    get: () => props.modelValue,
    set: val => emit('update:modelValue', val)
})

const handleSubmit = async () => {
    await submitImplementation({
        mode: props.mode,
        implementationId: props.funded_project?.latest_funding?.latest_implementation?.id ?? null,
        fundedProjectId: props.funded_project?.latest_funding?.id,
        onSuccess: () => {
            emit('form-submitted', props.mode)
            resetForm()
        }
    })
}

watch(
    () => [props.modelValue, props.implementation],
    ([isOpen, implementation]) => {
        if (isOpen && props.mode === 'edit' && implementation) {
            const data = Array.isArray(implementation) ? implementation[0] : implementation

            implementationForm.date_received = data.date_received ? data.date_received.split(' ')[0] : ''
            implementationForm.employee_id = data.employee_id || ''
            implementationForm.date_start = data.date_start ? data.date_start.split(' ')[0] : ''
            implementationForm.date_issued = data.date_issued ? data.date_issued.split(' ')[0] : ''
        }
    },
    { immediate: true }
)

onMounted(() => {
    fetchEmployees()
    console.log("Selected project:", props.implementation)
})
</script>

<style lang="scss" scoped></style>