<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="bg-dialogbg border border-drawerborder text-textsecondary sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Add Monthly Schedule</DialogTitle>
                <DialogDescription>
                    Duration {{ duration }} days
                </DialogDescription>
            </DialogHeader>

            <div v-for="(item, index) in monthTargets" :key="index" class="flex items-center gap-4 mb-2">

                <span class="w-40">
                    {{ new Date(item.rawMonth).toLocaleString("default", { month: "long", year: "numeric" }) }}
                </span>
                <FormInput v-model="item.target" type="number" placeholder="Enter target"
                    class="border rounded px-2 py-1 w-32" />
            </div>

            <DialogFooter class="sm:justify-end text-textprimary">
                <Button type="button" variant="newprimary" size="lg" :disabled="isSubmitting" @click="saveSchedule">
                    <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                    <span v-else>Submit</span>
                </Button>
                <DialogClose as-child>
                    <Button type="button" variant="secondary" size="lg">
                        Close
                    </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
const { api } = useAxios()
import { toast } from '@/components/ui/toast'
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

const isSubmitting = ref(false)

const saveSchedule = async () => {
    const data = monthTargets.value.map(item => ({
        rawMonth: item.rawMonth,
        target: item.target || 0
    }))

    const sum = data.reduce((s, it) => s + Number(it.target), 0)
    if (Math.abs(sum - 1) > 1e-6) {
        toast({
            title: 'Validation error',
            description: `Targets must sum to 1 (current sum: ${sum})`
        })
        return
    }

    isSubmitting.value = true
    try {
        await api.post('/api/implementation/monthly_targets', {
            implementation_id: props.implementation.id,
            targets: data
        })
        emit('form-submitted')
    } catch (error) {
        console.error(error)
    } finally {
        isSubmitting.value = false
    }
}

const schedule = ref([])
const monthTargets = ref([])

function generateMonthsFromStart(startDateStr, durationDays) {
    if (!startDateStr) return []
    // parse as local date without timezone shift
    const parts = startDateStr.split('-').map(Number)
    // parts = [yyyy, mm, dd]
    const start = new Date(parts[0], parts[1] - 1, parts[2])
    const months = []

    // Number of months to generate: approximate by dividing days by 30 and rounding up
    const monthsCount = Math.max(1, Math.ceil(Number(durationDays) / 30))

    for (let i = 0; i < monthsCount; i++) {
        const d = new Date(start.getFullYear(), start.getMonth() + i, 1)
        // avoid timezone shifts by formatting manually
        const yyyy = d.getFullYear()
        const mm = String(d.getMonth() + 1).padStart(2, '0')
        const dd = String(d.getDate()).padStart(2, '0')
        months.push(`${yyyy}-${mm}-${dd}`)
    }

    return months
}

const loadMonthTargets = async () => {
    const expectedMonths = generateMonthsFromStart(props.implementation?.date_start, props.duration)
    if (props.mode === 'add') {
        monthTargets.value = expectedMonths.map(m => ({ rawMonth: m, target: "" }))
    } else {
        // Edit mode: generate months from current start_date, populate with existing targets if available
        try {
            const response = await api.get(
                `/api/implementation/${props.implementationId}/target`
            )
            const existing = response.data.targets || []
            // Create a map of month to target for quick lookup
            const existingMap = {}
            existing.forEach(t => {
                const monthKey = t.month || t.rawMonth
                existingMap[monthKey] = t.target
            })
            monthTargets.value = expectedMonths.map(m => ({
                rawMonth: m,
                target: existingMap[m] || ""
            }))
        } catch (err) {
            console.error('failed to fetch existing schedule targets', err)
            // Fallback to empty targets
            monthTargets.value = expectedMonths.map(m => ({ rawMonth: m, target: "" }))
        }
    }
}

onMounted(() => {
    loadMonthTargets()
})

watch(() => isOpen, (val) => {
    if (val) loadMonthTargets()
})

watch(() => props.implementation?.date_start, () => {
    if (isOpen) loadMonthTargets()
})


</script>
