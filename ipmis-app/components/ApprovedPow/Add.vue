<template>
    <div>
        <RightDrawer v-model:isOpen="isOpen">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Approved POW</h2>
                </div>
            </template>

            <form @submit.prevent="handleSubmit">
                <FormGroup placeholder="File Link" v-model="approvedPowForm.file_link"
                    :errorMessage="errorBag.file_link" name="file_link" type="text" label="File Link" class="mb-8"/>

                <div class="grid grid-cols-1 md:grid-cols-1 flex-grow mb-8">
                    <FormLabel>Remarks</FormLabel>
                    <Textarea placeholder="Remarks" v-model="approvedPowForm.remarks"
                        :errorMessage="errorBag.remarks" />
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
const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()
const { approvedPowForm, submitApprovedPow, isSubmitting } = useApprovedPows()

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

onMounted(() => {
    console.log("For approval POW", props.pre_engineering)
})
</script>

<style lang="scss" scoped></style>