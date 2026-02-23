<template>
    <div>
        <p class="text-sm text-muted-foreground">{{ label }}:</p>
        <p class="text-sm font-medium">{{ value || 'No data' }}</p>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: [String, Number, null]
})
</script>
