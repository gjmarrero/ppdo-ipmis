<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle></DialogTitle>
                <DialogDescription>
                    Monthly Schedule {{ props.implementationId }} {{ duration }}
                </DialogDescription>
            </DialogHeader>

            <div v-for="(item, index) in monthTargets" :key="index" class="flex items-center gap-4 mb-2">
                <span class="w-40">
                    {{ new Date(item.rawMonth).toLocaleString("default", { month: "long", year: "numeric" }) }}
                </span>
                <FormInput v-model="item.target" type="number" placeholder="Enter target"
                    class="border rounded px-2 py-1 w-32" />
            </div>

            <DialogFooter class="sm:justify-start">
                <Button @click="saveSchedule">Save</Button>
                <DialogClose as-child>                    
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

const isOpen = defineModel('isOpen', { type: Boolean, default: false })

const props = defineProps({
    implementation: Object,
    duration: Number,
    mode: {
        default: 'add',
        type: String
    },
    implementationId: {
        defaul: null,
        type: String
    }
})

const emit = defineEmits(['form-submitted'])

const saveSchedule = async () => {
    console.log("Saving")
    const data = monthTargets.value.map(item => ({
        rawMonth: item.rawMonth,
        target: item.target || 0
    }))

    try {
        await api.post('/api/implementation/monthly_targets', {
            implementation_id: props.implementation.id,
            targets: data
        })
        emit('form-submitted')
    } catch (error) {
        console.error(error)
    }
}

const schedule = ref([])
const monthTargets = ref([])

onMounted(async () => {
    if (props.mode === 'add') {
        const response = await api.get(
            `/api/implementation/${props.implementation.id}/schedule/${props.duration}`
        )
        schedule.value = response.data

        monthTargets.value = schedule.value.months.map((m) => ({
            rawMonth: m,
            target: "",
        }))
    } else {
        const response = await api.get(
            `/api/implementation/${props.implementationId}/target`
        )
        monthTargets.value = response.data.targets.map(m => ({
            id: m.id,
            rawMonth: m.month,
            target: m.target ?? ""
        }))
    }

})


</script>
