<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary w-full max-w-4xl"
            @open-auto-focus="(e) => e.preventDefault()">
            <DialogHeader>
                <DialogTitle>Add Inspection Schedule</DialogTitle>
                <DialogDescription>

                </DialogDescription>
            </DialogHeader>
            <div class="grid md:grid-cols-3 sm:grid-cols-1 gap-4">
                <div>
                    <FormGroup placeholder="Date Request Received" v-model="inspectionForm.date_request_received"
                        :errorMessage="errorBag.date_request_received" name="date_request_received"
                        label="Date Request Received" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date of Pre-inspection" v-model="inspectionForm.date_pre_inspection"
                        :errorMessage="errorBag.date_pre_inspection" name="date_pre_inspection"
                        label="Date of Pre-inspection" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date of Post-inspection" v-model="inspectionForm.date_post_inspection"
                        :errorMessage="errorBag.date_post_inspection" name="date_post_inspection"
                        label="Date of Post-inspection" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date of Final Inspection" v-model="inspectionForm.date_final_inspection"
                        :errorMessage="errorBag.date_final_inspection" name="date_final_inspection"
                        label="Date of Final Inspection" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Report Prepared" v-model="inspectionForm.date_report_prepared"
                        :errorMessage="errorBag.date_report_prepared" name="date_report_prepared"
                        label="Date Report Prepared" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date File Compilation Prepared"
                        v-model="inspectionForm.date_file_compilation_prepared"
                        :errorMessage="errorBag.date_file_compilation_prepared" name="date_file_compilation_prepared"
                        label="Date File Compilation Prepared" type="date" />
                </div>
                <div class="md:col-span-3">
                    <FormLabel>Remarks</FormLabel>
                    <Textarea placeholder="Description" v-model="inspectionForm.remarks"
                        :errorMessage="errorBag.remarks" name="remarks" label="Remarks" class="text-black w-full" />
                </div>
                <div class="md:col-span-3">
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
                </div>
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

const props = defineProps({
    implementation: Object,
    mode: {
        type: String,
        default: 'add'
    },
    inspection: {
        type: Object,
        default: null
    }
})

const isOpen = defineModel('isOpen', { type: Boolean, default: false })

const emit = defineEmits(['inspection-submitted'])

const inspectionForm = reactive({
    date_request_received: '',
    date_pre_inspection: '',
    date_post_inspection: '',
    date_final_inspection: '',
    date_report_prepared: '',
    date_file_compilation_prepared: '',
    remarks: '',
    inspection_file: null,
})

const fileInputRef = ref(null)

const triggerFilePicker = () => {
    fileInputRef.value?.click()
}

const handleFileChange = (event) => {
    inspectionForm.inspection_file = event.target.files?.[0] ?? null
}

const saveInspection = async () => {
    const formDataObject = new FormData()

    Object.keys(inspectionForm).forEach(key => {
        if (key === 'inspection_file') {
            if (inspectionForm.inspection_file) {
                formDataObject.append(key, inspectionForm.inspection_file)
            }
            return
        }

        formDataObject.append(key, inspectionForm[key] ?? '')
    })

    let url = `/api/implementation/${props.implementation.id}/inspection`

    if (props.mode === 'edit' && props.inspection?.id) {
        url = `/api/implementation/inspection/${props.inspection.id}?_method=PUT`
    }

    await api.post(url, formDataObject, {
        headers: { 'Content-Type': 'multipart/form-data' }
    })

    emit('inspection-submitted')
}

const setInspectionForm = (inspection) => {
    if (!inspection) return

    Object.assign(inspectionForm, {
        date_request_received: inspection.date_request_received ?? '',
        date_pre_inspection: inspection.date_pre_inspection ?? '',
        date_post_inspection: inspection.date_post_inspection ?? '',
        date_final_inspection: inspection.date_final_inspection ?? '',
        date_report_prepared: inspection.date_report_prepared ?? '',
        date_file_compilation_prepared: inspection.date_file_compilation_prepared ?? '',
        remarks: inspection.remarks ?? '',
        inspection_file: null
    })
}

onMounted(async () => {
    if (props.mode === 'edit' && props.inspection) {
        setInspectionForm(props.inspection)
    }
})

watch(
    () => [props.mode, props.inspection, isOpen.value],
    ([mode, inspection, open]) => {
        if (!open) return
        if (mode === 'edit' && inspection) {
            setInspectionForm(inspection)
            return
        }

        Object.assign(inspectionForm, {
            date_request_received: '',
            date_pre_inspection: '',
            date_post_inspection: '',
            date_final_inspection: '',
            date_report_prepared: '',
            date_file_compilation_prepared: '',
            remarks: '',
            inspection_file: null
        })
    },
    { immediate: true }
)

</script>

<style lang="scss" scoped></style>


<style lang="scss" scoped></style>
