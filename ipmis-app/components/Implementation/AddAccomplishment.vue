<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="max-w-4xl w-full bg-dialogbg border border-drawerborder text-textsecondary">
            <DialogHeader>
                <DialogTitle></DialogTitle>
                <DialogDescription>
                    Add or Update Accomplishment
                </DialogDescription>
            </DialogHeader>
            <div v-for="(item, index) in activeAccomplishments" :key="item.id"
                class="p-2 border border-borderblackui rounded mb-1 flex justify-between items-center">
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

            <div class="flex flex-row justify-end text-textprimary gap-2 mt-4">
                <Button type="button" variant="newprimary" size="lg" :disabled="isSubmitting" @click="saveChanges">
                    <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                    <span v-else>Submit</span>
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>

<script setup>
const { api } = useAxios()
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

const isSubmitting = ref(false)

const activeAccomplishments = computed(() => {
    return props.implementation?.monthly_schedule_accomplishment?.filter(item => item.is_active) || []
})

const initialFormData = ref([])

const formData = ref([])

watch(
    () => activeAccomplishments.value,
    (newVal) => {
        const mapped = newVal.map(item => ({
            id: item.id,
            actual: item.actual
        }))

        formData.value = mapped
        initialFormData.value = JSON.parse(JSON.stringify(mapped))
    },
    { immediate: true }
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
    console.log(formData.value, initialFormData.value)

    isSubmitting.value = true

    const changed = formData.value.filter(item => {
        const initialItem = initialFormData.value?.find(
            i => i.id === item.id
        )
        console.log("error in changed")
        return initialItem && item.actual !== initialItem.actual
    })

    if (changed.length === 0) {
        isSubmitting.value = false
        console.log("error in length")
        return
    }

    try {
        await api.post("/api/accomplishments/batch_update", changed)
        console.log("Saved successfully")
        emit('form-submitted')
    } catch (error) {
        console.error("Save failed", error)
    } finally {
        isSubmitting.value = false
    }
}

// const saveChanges = async () => {
//     console.log('formData length:', formData.value, 'initialFormData length:', initialFormData.value)
//     isSubmitting.value = true
//     const changed = formData.value.filter((item, idx) => {
//         return item.actual !== initialFormData.value[idx].actual
//     })

//     if (changed.length === 0) return

//     try {
//         await api.post("/api/accomplishments/batch_update", changed)
//         console.log("Saved successfully")
//         emit('form-submitted')
//     } catch (error) {
//         console.error("Save failed", error)
//     } finally {
//         isSubmitting.value = false
//     }
// }


</script>
