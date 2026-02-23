<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle></DialogTitle>
                <DialogDescription>
                    Status
                </DialogDescription>
            </DialogHeader>

            <div>
                <FormLabel>Status Update</FormLabel>
                <FormCombobox :options="statusOptions" placeholder="Select status" v-model="statusForm.status"
                    :errorMessage="errorBag.status" />
            </div>
            <div>
                <FormGroup placeholder="Barcode (if applicable)" v-model="statusForm.dts_barcode" :errorMessage="errorBag.dts_barcode"
                    name="dts_barcode" label="DTS barcode (if applicable)" />
            </div>

            <DialogFooter class="sm:justify-start">
                <DialogClose as-child>
                    <Button @click="">Save</Button>
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
import Textarea from "../ui/textarea/Textarea.vue"

const { errorBag } = useCustomError()
const isOpen = defineModel('isOpen', { type: Boolean, default: false })

const props = defineProps({
    implementation: Object,
})

const statusOptions = [
    { id: 1, name: 'Suspension' },
    { id: 2, name: 'Termination' },
    { id: 3, name: 'Extension'},
    { id: 4, name: 'Variation'}
]

const statusForm = reactive({
    status: '',
    dts_barcode: '',
})
// const saveSchedule = async () => {
//     const data = monthTargets.value.map(item => ({
//         rawMonth: item.rawMonth,
//         target: item.target || 0
//     }))

//     try {
//         await api.post('/api/implementation/monthly_targets', {
//             implementation_id: props.implementation[0].id,
//             targets: data
//         })
//     } catch (error) {
//         console.error(error)
//     }
// }

// const schedule = ref([])
// const monthTargets = ref([])

onMounted(async () => {
    // const response = await api.get(
    //     `/api/implementation/${props.implementation[0]?.id}/schedule/${props.duration}`
    // )
    // schedule.value = response.data

    // monthTargets.value = schedule.value.months.map((m) => ({
    //     rawMonth: m,
    //     target: "",
    // }))
})


</script>
