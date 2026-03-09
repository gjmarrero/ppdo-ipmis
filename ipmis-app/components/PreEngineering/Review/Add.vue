<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">{{mode.toLowerCase().replace(/\b\w/g, c =>
                        c.toUpperCase())
                    }} Review Status</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8 gap-2">
                    <FormGroup placeholder="Date received by APE" v-model="reviewForm.date_received_by_ape"
                        :errorMessage="errorBag.date_received_by_ape" name="date_received_by_ape" type="date"
                        label="Date received by APE" />
                    <FormGroup placeholder="Date Reviewed" v-model="reviewForm.date_reviewed"
                        :errorMessage="errorBag.date_reviewed" name="date_reviewed" type="date" label="Date Reviewed" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow mb-8 gap-2">
                    <FormGroup placeholder="Date Recommended for Approval"
                        v-model="reviewForm.date_recommended_for_approval"
                        :errorMessage="errorBag.date_recommended_for_approval" name="date_recommended_for_approval"
                        type="date" label="Date Recommended for Approval" />
                    <FormGroup placeholder="Date Submitted to LCE" v-model="reviewForm.date_submitted_to_lce"
                        :errorMessage="errorBag.date_submitted_to_lce" name="date_submitted_to_lce" type="date"
                        label="Date Submitted to LCE" />
                </div>

                <div class="flex flex-row justify-end">
                    <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmittingReview">
                        <Spinner v-if="isSubmittingReview" :show="true" size="lg" label="Saving..." />
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
const { reviewForm, submitReviewStatus, isSubmittingReview } = usePreEngineerings()

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
    await submitReviewStatus({
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

            reviewForm.date_received_by_ape = data.date_received_by_ape ? data.date_received_by_ape.split(' ')[0] : ''
            reviewForm.date_reviewed = data.date_reviewed ? data.date_reviewed.split(' ')[0] : ''
            reviewForm.date_recommended_for_approval = data.date_recommended_for_approval ? data.date_recommended_for_approval.split(' ')[0] : ''
            reviewForm.date_submitted_to_lce = data.date_submitted_to_lce ? data.date_submitted_to_lce.split(' ')[0] : ''
        }
    },
    { immediate: true }
)


onMounted(async () => {
    fetchEmployees()
})

</script>

<style lang="scss" scoped></style>