<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Approve POW</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <FormGroup placeholder="Date POW approved" v-model="forApprovalPowForm.date_approved_lce"
                        :errorMessage="errorBag.date_approved_lce" name="date_approved_lce" type="date"
                        label="Date POW approved" />

                <div class="flex flex-row justify-end pt-6">
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
const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()
const { forApprovalPowForm, submitApprovedPow, isSubmitting } = usePowForApproval()

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
    await submitApprovedPow({
        preEngineeringId: props.pre_engineering?.id ?? null,
        onSuccess: () => {
            emit('form-submitted')
        }
    })
}

onMounted( () => {
    console.log("For approval POW", props.pre_engineering)
})
</script>

<style lang="scss" scoped></style>