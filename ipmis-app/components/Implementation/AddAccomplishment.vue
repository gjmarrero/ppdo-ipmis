<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="max-w-4xl w-full">
            <DialogHeader>
                <DialogTitle></DialogTitle>
                <DialogDescription>
                    Add or Update Accomplishment
                </DialogDescription>
            </DialogHeader>
            <div v-for="(item, index) in implementation.monthly_schedule_accomplishment" :key="item.id"
                class="p-2 border rounded mb-1 flex justify-between items-center">
                <div>
                    <p class="font-semibold">
                        {{ formatMonth(item.month) }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        Target: {{ item.target }}
                    </p>
                </div>
                <div>
                    <div v-if="canEdit(item.month)" class="w-40">
                        <FormInput v-model="formData[index].actual" type="number" step="0.01"
                            placeholder="Enter accomplishment" />
                    </div>
                    <div v-else class="w-40">
                        <span>{{ item.actual !== null ? item.actual : "Pending" }}</span>
                    </div>
                </div>
            </div>

            <Button @click="saveChanges">Save</Button>
        </DialogContent>
    </Dialog>
</template>

<script setup>
const { api } = useAxios()
import { Button } from "@/components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
} from "@/components/ui/dialog"

const isOpen = defineModel('isOpen', { type: Boolean, default: false })

const props = defineProps({
    implementation: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['form-submitted'])

const formData = ref(
    props.implementation.monthly_schedule_accomplishment.map(item => ({
        id: item.id,
        month: item.month,
        target: item.target,
        actual: item.actual
    }))
)

const formatMonth = (monthStr) => {
    return new Date(monthStr).toLocaleString("default", {
        month: "long",
        year: "numeric"
    })
}

const canEdit = (month) => {
    const current = new Date()
    const target = new Date(month)

    const isPast =
        target.getFullYear() < current.getFullYear() ||
        (target.getFullYear() === current.getFullYear() &&
            target.getMonth() < current.getMonth())

    const isCurrent =
        target.getFullYear() === current.getFullYear() &&
        target.getMonth() === current.getMonth()

    return isPast || isCurrent
}

const saveChanges = async () => {
    console.log("props imp", props.implementation)
    const changed = formData.value.filter((item, idx) => {
        const original = props.implementation.monthly_schedule_accomplishment[idx]
        return item.actual !== original.actual
    })

    if (changed.length === 0) return

    try {
        await api.post("/api/accomplishments/batch_update", changed)
        console.log("Saved successfully")
        emit('form-submitted')
    } catch (error) {
        console.error("Save failed", error)
    }
}


</script>
