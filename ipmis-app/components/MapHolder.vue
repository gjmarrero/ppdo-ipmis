<template>
    <Dialog :open="props.isMapDialogOpen" @update:open="(val) => emit('update:isMapDialogOpen', val)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Map</DialogTitle>
            </DialogHeader>
            <ClientOnly>
                <LeafletMap v-if="showMap && props.marker" :markers="[props.marker]" />
            </ClientOnly>

        </DialogContent>
        <DialogFooter>

        </DialogFooter>
    </Dialog>
</template>

<script setup>

const props = defineProps({
    isMapDialogOpen: Boolean,
    marker: {
        type: Object,
        default: () => null
    }
})

const emit = defineEmits(['update:isMapDialogOpen'])

const showMap = ref(false)

watch(() => props.isMapDialogOpen, async (open) => {
    if (open) {
        await nextTick()
        showMap.value = true
    } else {
        showMap.value = false
    }
})

</script>

<style lang="scss" scoped></style>