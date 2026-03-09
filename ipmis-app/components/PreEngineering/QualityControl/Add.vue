<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c =>
                        c.toUpperCase())
                        }} quality control plan data</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8 gap-2">
                    <FormGroup placeholder="Date received by QC" v-model="qualityControlForm.date_received_by_qc"
                        :errorMessage="errorBag.date_received_by_qc" name="date_received_by_qc" type="date"
                        label="Date received by QC" />
                    <div>
                        <FormLabel>Name of QC in-charge</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select name of employee"
                            v-model="qualityControlForm.employee_id_qcp" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8 gap-2">
                    <FormGroup placeholder="Date QCP prepared" v-model="qualityControlForm.date_qcp_prepared"
                        :errorMessage="errorBag.date_qcp_prepared" name="date_qcp_prepared" type="date"
                        label="Date QCP prepared" />
                    <FormGroup placeholder="Date QCP reviewed" v-model="qualityControlForm.date_qcp_reviewed"
                        :errorMessage="errorBag.date_qcp_reviewed" name="date_qcp_reviewed" type="date"
                        label="Date QCP reviewed" />
                </div>

                <div class="flex flex-row justify-end">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmittingQcp">
                        <Spinner v-if="isSubmittingQcp" :show="true" size="lg" label="Saving..." />
                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>
const { errorBag } = useCustomError()
const { employees, fetchEmployees } = useEmployees()
const { qualityControlForm, submitQualityControl, resetQualityControlForm, isSubmittingQcp } = usePreEngineerings()

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

const handleSubmit = async () => {
    await submitQualityControl({
        preEngineeringId: props.funded_project?.latest_funding?.latest_preengineering?.id ?? null,
        onSuccess: () => {
            emit('form-submitted', props.mode)
        }
    })
}

watch(
    () => [props.modelValue, props.funded_project?.latest_funding?.latest_preengineering],
    ([isOpen, preEng]) => {
        if (isOpen && props.mode === 'edit' && preEng) {
            const data = Array.isArray(preEng) ? preEng[0] : preEng

            qualityControlForm.employee_id_qcp = data.employee_id_qcp || ''
            qualityControlForm.date_received_by_qc = data.date_received_by_qc ? data.date_received_by_qc.split(' ')[0] : ''
            qualityControlForm.date_qcp_prepared = data.date_qcp_prepared ? data.date_qcp_prepared.split(' ')[0] : ''
            qualityControlForm.date_qcp_reviewed = data.date_qcp_reviewed ? data.date_qcp_reviewed.split(' ')[0] : ''
        }
    },
    { immediate: true }
)


onMounted(async () => {
    fetchEmployees()
})

</script>

<style lang="scss" scoped></style>