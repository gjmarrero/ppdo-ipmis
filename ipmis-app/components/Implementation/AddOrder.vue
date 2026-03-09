<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary w-full max-w-4xl"
            @open-auto-focus="(e) => e.preventDefault()">
            <DialogHeader>
                <DialogTitle>Add Order</DialogTitle>
                <DialogDescription>
                    
                </DialogDescription>
            </DialogHeader>
            <div class="grid md:grid-cols-3 sm:grid-cols-1 gap-4">
                <div>
                    <FormLabel>Type of Order</FormLabel>
                    <FormCombobox :options="orderOptions" placeholder="Select order type" v-model="orderForm.order_type"
                        :errorMessage="errorBag.order_type" />
                </div>
                <div>
                    <FormGroup placeholder="Barcode (if applicable)" v-model="orderForm.dts_barcode"
                        :errorMessage="errorBag.dts_barcode" name="dts_barcode" label="DTS barcode (if applicable)" />
                </div>
                <div>
                    <FormGroup placeholder="Date Prepared" v-model="orderForm.date_prepared"
                        :errorMessage="errorBag.date_prepared" name="date_prepared" label="Date Prepared" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Checked" v-model="orderForm.date_checked"
                        :errorMessage="errorBag.date_checked" name="date_checked" label="Date Checked" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Reviewed" v-model="orderForm.date_reviewed"
                        :errorMessage="errorBag.date_reviewed" name="date_reviewed" label="Date Reviewed" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Recommended for Approval"
                        v-model="orderForm.date_recommended_for_approval"
                        :errorMessage="errorBag.date_recommended_for_approval" name="date_recommended_for_approval"
                        label="Date Recommended for Approval" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Forwarded to LCE" v-model="orderForm.date_forwarded_to_lce"
                        :errorMessage="errorBag.date_forwarded_to_lce" name="date_forwarded_to_lce"
                        label="Date Forwarded to LCE" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Approved by LCE" v-model="orderForm.date_approved_by_lce"
                        :errorMessage="errorBag.date_approved_by_lce" name="date_approved_by_lce"
                        label="Date Approved by LCE" type="date" />
                </div>
                <div>
                    <FormGroup placeholder="Date Signed by Contractor" v-model="orderForm.date_signed_by_contractor"
                        :errorMessage="errorBag.date_signed_by_contractor" name="date_signed_by_contractor"
                        label="Date Signed by Contractor" type="date" />
                </div>
                <div class="md:col-span-3">
                    <FormLabel>Attachment</FormLabel>
                    <input ref="fileInputRef" type="file" class="hidden" @change="handleFileChange" />
                    <div class="mt-1 flex items-center gap-2">
                        <Button type="button" variant="newsecondary" @click="triggerFilePicker">
                            Choose File
                        </Button>
                        <span class="text-sm text-textsecondary">
                            {{ orderForm.order_file?.name || 'No file selected' }}
                        </span>
                    </div>
                    <p v-if="errorBag.order_file" class="mt-1 text-sm text-red-500">{{ errorBag.order_file }}</p>
                </div>
            </div>



            <div class="flex flex-row justify-end gap-2 text-textprimary">
                <DialogFooter class="sm:justify-start">
                    <Button type="button" @click="saveOrder" variant="newprimary">Save</Button>
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
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
} from "@/components/ui/dialog"

const { errorBag } = useCustomError()

const isOpen = defineModel('isOpen', { type: Boolean, default: false })

const props = defineProps({
    implementation: Object,
    order: {
        type: Object,
        default: null
    },
    mode: {
        type: String,
        default: 'add'
    }
})

const emit = defineEmits(['order-submitted'])

const orderOptions = [
    { id: 'Change/Variation Order', name: 'Change/Variation Order' },
    { id: 'Extension Order', name: 'Extension Order' },
    { id: 'Supension', name: 'Suspension' },
    { id: 'Supplemental Aggreement', name: 'Supplemental Agreement' },
    { id: 'Termination', name: 'Termination' },
]

const orderForm = reactive({
    order_type: '',
    dts_barcode: '',
    date_prepared: '',
    date_checked: '',
    date_reviewed: '',
    date_recommended_for_approval: '',
    date_forwarded_to_lce: '',
    date_approved_by_lce: '',
    date_signed_by_contractor: '',
    order_file: null,
})

const fileInputRef = ref(null)

const triggerFilePicker = () => {
    fileInputRef.value?.click()
}

const handleFileChange = (event) => {
    orderForm.order_file = event.target.files?.[0] ?? null
}

const saveOrder = async () => {
    const formDataObject = new FormData()

    Object.keys(orderForm).forEach(key => {
        if (key === 'order_file') {
            if (orderForm.order_file) {
                formDataObject.append(key, orderForm.order_file)
            }
            return
        }

        formDataObject.append(key, orderForm[key] ?? '')
    })

    let url = `/api/implementation/${props.implementation.id}/order`

    if (props.mode === 'edit' && props.order?.id) {
        url = `/api/implementation/order/${props.order.id}?_method=PUT`
    }

    await api.post(url, formDataObject, {
        headers: { 'Content-Type': 'multipart/form-data' }
    })

    emit('order-submitted')
}

const setOrderForm = (order) => {
    if (!order) return

    Object.assign(orderForm, {
        order_type: order.order_type ?? '',
        dts_barcode: order.dts_barcode ?? '',
        date_prepared: order.date_prepared ?? '',
        date_checked: order.date_checked ?? '',
        date_reviewed: order.date_reviewed ?? '',
        date_recommended_for_approval: order.date_recommended_for_approval ?? '',
        date_forwarded_to_lce: order.date_forwarded_to_lce ?? '',
        date_approved_by_lce: order.date_approved_by_lce ?? '',
        date_signed_by_contractor: order.date_signed_by_contractor ?? '',
        order_file: null
    })
}

onMounted(async () => {
    if (props.mode === 'edit' && props.order) {
        setOrderForm(props.order)
    }
})

watch(
    () => [props.mode, props.order, isOpen.value],
    ([mode, order, open]) => {
        if (!open) return
        if (mode === 'edit' && order) {
            setOrderForm(order)
            return
        }

        Object.assign(orderForm, {
            order_type: '',
            dts_barcode: '',
            date_prepared: '',
            date_checked: '',
            date_reviewed: '',
            date_recommended_for_approval: '',
            date_forwarded_to_lce: '',
            date_approved_by_lce: '',
            date_signed_by_contractor: '',
            order_file: null
        })
    },
    { immediate: true }
)


</script>
