<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary w-full max-w-4xl"
            @open-auto-focus="(e) => e.preventDefault()">
            <DialogHeader>
                <DialogTitle>Add QC Schedule</DialogTitle>
                <DialogDescription>

                </DialogDescription>
            </DialogHeader>
            <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4">
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <FormLabel>Construction Materials Needed?</FormLabel>
                        <Switch v-model="isConstructionMaterialsNeeded" />
                    </div>
                    <div v-if="isConstructionMaterialsNeeded">
                        <FormLabel>Name of QC-in-Charge</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select name of QC-in-Charge"
                            v-model="qualityControlForm.qc_in_charge" :disabled="!isConstructionMaterialsNeeded" />
                    </div>
                </div>

                <div v-if="isConstructionMaterialsNeeded" class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <FormGroup placeholder="Date Materials Inspected"
                            v-model="qualityControlForm.date_materials_inspected"
                            :errorMessage="errorBag.date_materials_inspected" name="date_materials_inspected"
                            label="Date Materials Inspected" type="date" :disabled="!isConstructionMaterialsNeeded" />
                    </div>
                    <div>
                        <FormLabel>Materials Approved?</FormLabel>
                        <Switch v-model="isMaterialsApproved" />
                    </div>
                    <div v-if="isMaterialsApproved">
                        <FormGroup placeholder="Date Replacement of Materials Requested"
                            v-model="qualityControlForm.date_replacement_requested"
                            :errorMessage="errorBag.date_replacement_requested" name="date_replacement_requested"
                            label="Date Replacement of Materials Requested" type="date"
                            :disabled="!isConstructionMaterialsNeeded" />
                    </div>
                </div>

                <div v-if="isConstructionMaterialsNeeded" class="md:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <FormLabel>Pouring Permit Required</FormLabel>
                        <Switch v-model="isPouringPermitNeeded" />
                    </div>
                    <div v-if="isPouringPermitNeeded">
                        <FormGroup placeholder="Date of Pouring Permit Issued"
                            v-model="qualityControlForm.date_pouring_permit_issued"
                            :errorMessage="errorBag.date_pouring_permit_issued" name="date_pouring_permit_issued"
                            label="Date of Pouring Permit Issued" type="date" :disabled="!isPouringPermitNeeded" />
                    </div>
                    <div v-if="isPouringPermitNeeded">
                        <FormGroup placeholder="Date of Pouring Inspected"
                            v-model="qualityControlForm.date_pouring_inspected"
                            :errorMessage="errorBag.date_pouring_inspected" name="date_pouring_inspected"
                            label="Date of Pouring Inspected" type="date" :disabled="!isPouringPermitNeeded" />
                    </div>
                    <div v-if="isPouringPermitNeeded">
                        <FormLabel>Is Pouring Approved</FormLabel>
                        <Switch v-model="isPouringApproved" />
                    </div>
                </div>

                <div v-if="isConstructionMaterialsNeeded && isPouringPermitNeeded && !isPouringApproved"
                    class="md:col-span-2">
                    <FormGroup placeholder="Date Requested Rejected Pouring for Fixing"
                        v-model="qualityControlForm.date_rejected_pouring_requested"
                        :errorMessage="errorBag.date_rejected_pouring_requested" name="date_rejected_pouring_requested"
                        label="Date Requested Rejected Pouring for Fixing" type="date"
                        :disabled="!isPouringPermitNeeded" />
                </div>

                <div v-if="isConstructionMaterialsNeeded" class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <FormGroup placeholder="Date Samples Tested" v-model="qualityControlForm.date_samples_tested"
                            :errorMessage="errorBag.date_samples_tested" name="date_samples_tested"
                            label="Date Samples Tested" type="date" />
                    </div>
                    <div>
                        <FormLabel>Samples Passed</FormLabel>
                        <Switch v-model="isSamplesApproved" />
                    </div>
                    <div v-if="!isSamplesApproved">
                        <FormGroup placeholder="Date Rejected Samples Reported"
                            v-model="qualityControlForm.date_rejected_samples_reported"
                            :errorMessage="errorBag.date_rejected_samples_reported" name="date_rejected_samples_reported"
                            label="Date Rejected Samples Reported" type="date" />
                    </div>
                </div>

                <div v-if="isConstructionMaterialsNeeded" class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <FormGroup placeholder="Date IR OP Prepared" v-model="qualityControlForm.date_ir_op_prepared"
                            :errorMessage="errorBag.date_ir_op_prepared" name="date_ir_op_prepared"
                            label="Date IR OP Prepared" type="date" />
                    </div>
                    <div>
                        <FormGroup placeholder="Date IR Checked" v-model="qualityControlForm.date_ir_checked"
                            :errorMessage="errorBag.date_ir_checked" name="date_ir_checked" label="Date IR Checked"
                            type="date" />
                    </div>
                    <div>
                        <FormGroup placeholder="Date IR Approved" v-model="qualityControlForm.date_ir_approved"
                            :errorMessage="errorBag.date_ir_approved" name="date_ir_approved" label="Date IR Approved"
                            type="date" />
                    </div>
                </div>
                <!-- <div class="md:col-span-3">
                    <FormLabel>Attachment</FormLabel>
                    <input ref="fileInputRef" type="file" class="hidden" @change="handleFileChange" />
                    <div class="mt-1 flex items-center gap-2">
                        <Button type="button" variant="newsecondary" @click="triggerFilePicker">
                            Choose File
                        </Button>
                        <span class="text-sm text-textsecondary">
                            {{ inspectionForm.inspection_file?.name || 'No file selected' }}
                        </span>
                    </div>
                    <p v-if="errorBag.inspection_file" class="mt-1 text-sm text-red-500">{{ errorBag.inspection_file }}
                    </p>
                </div> -->
            </div>



            <div class="flex flex-row justify-end gap-2 text-textprimary">
                <DialogFooter class="sm:justify-start">
                    <Button type="button" @click="saveInspection" variant="newprimary">Save</Button>
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">
                            Close
                        </Button>
                    </DialogClose>
                </DialogFooter>
            </div>
        </DialogContent>
    </Dialog>
</template>

<script setup>

const { api } = useAxios()

const { errorBag } = useCustomError()
const { employees, fetchEmployees } = useEmployees()

const props = defineProps({
    implementation: Object,
    inspection: {
        type: Object,
        default: null,
    },
    mode: {
        type: String,
        default: 'add',
    }
})

const isOpen = defineModel('isOpen', { type: Boolean, default: false })

const emit = defineEmits(['update:isOpen', 'qualityControlSaved'])

const isConstructionMaterialsNeeded = ref(false)
const isMaterialsApproved = ref(false)
const isPouringPermitNeeded = ref(false)
const isPouringApproved = ref(false)
const isSamplesApproved = ref(false)

const qualityControlForm = reactive({
    is_construction_materials_need: '',
    qc_in_charge: '',
    date_materials_inspected: '',
    is_materials_approved: '',
    date_replacement_requested: '',
    is_pouring_permit_needed: '',
    date_pouring_permit_issued: '',
    date_pouring_inspected: '',
    is_pouring_approved: '',
    date_rejected_pouring_requested: '',
    date_samples_tested: '',
    is_samples_approved: '',
    date_rejected_samples_reported: '',
    date_ir_op_prepared: '',
    date_ir_checked: '',
    date_ir_approved: '',
})

watch(isConstructionMaterialsNeeded, (value) => {
    qualityControlForm.is_construction_materials_need = value ? 'yes' : 'no'

    if (!value) {
        isPouringPermitNeeded.value = false
        qualityControlForm.qc_in_charge = ''
        qualityControlForm.date_materials_inspected = ''
        isMaterialsApproved.value = false
        qualityControlForm.date_replacement_requested = ''
        qualityControlForm.is_pouring_permit_needed = 'no'
        qualityControlForm.date_pouring_permit_issued = ''
        qualityControlForm.date_pouring_inspected = ''
        isPouringApproved.value = false
        qualityControlForm.is_pouring_approved = 'no'
        qualityControlForm.date_rejected_pouring_requested = ''
        qualityControlForm.date_samples_tested = ''
        isSamplesApproved.value = false
        qualityControlForm.is_samples_approved = 'no'
        qualityControlForm.date_rejected_samples_reported = ''
        qualityControlForm.date_ir_op_prepared = ''
        qualityControlForm.date_ir_checked = ''
        qualityControlForm.date_ir_approved = ''
    }
}, { immediate: true })

watch(isMaterialsApproved, (value) => {
    qualityControlForm.is_materials_approved = value ? 'yes' : 'no'

    if (!value) {
        qualityControlForm.date_replacement_requested = ''
    }

}, { immediate: true })

watch(isPouringPermitNeeded, (value) => {
    qualityControlForm.is_pouring_permit_needed = value ? 'yes' : 'no'

    if (!value) {
        qualityControlForm.date_pouring_permit_issued = ''
        qualityControlForm.date_pouring_inspected = ''
        qualityControlForm.date_rejected_pouring_requested = ''
    }
}, { immediate: true })

onMounted(() => {
    fetchEmployees()
})
</script>

<style lang="scss" scoped></style>
