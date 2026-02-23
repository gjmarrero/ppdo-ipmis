<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-md" @open-auto-focus="(e) => e.preventDefault()">
            <DialogHeader>
                <DialogTitle></DialogTitle>
                <DialogDescription>
                    Add Order
                </DialogDescription>
            </DialogHeader>

            <div>
                <FormLabel>Type of Order</FormLabel>
                <FormCombobox :options="orderOptions" placeholder="Select order type" v-model="orderForm.order_type"
                    :errorMessage="errorBag.order_type" />
            </div>
            <div>
                <FormGroup placeholder="Barcode (if applicable)" v-model="orderForm.dts_barcode" :errorMessage="errorBag.dts_barcode"
                    name="dts_barcode" label="DTS barcode (if applicable)" />
            </div>

            <DialogFooter class="sm:justify-start">
                <DialogClose as-child>
                    <Button @click="saveOrder">Save</Button>
                    <Button type="button" variant="secondary">
                        Close
                    </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
const { api } = useAxios()
import { Button } from "@/components/ui/button"
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
})

const emit = defineEmits(['order-submitted'])

const orderOptions = [
    { id: 'Supension', name: 'Suspension' },
    { id: 'Termination', name: 'Termination' },
    { id: 'Change/Variation Order', name: 'Change/Variation Order'},
    { id: 'Extension Order', name: 'Extension Order'}
]

const orderForm = reactive({
    order_type: '',
    dts_barcode: '',
})

const saveOrder = async() => {
    console.log("order props", props.implementation)
    await api.post(`/api/implementation/${props.implementation.id}/order`, {
        order_type: orderForm.order_type,
        dts_barcode: orderForm.dts_barcode
    })
    emit('order-submitted')
}

onMounted(async () => {

})


</script>
