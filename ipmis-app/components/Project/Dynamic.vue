<template>
    <div>
        <ProjectLayout class="py-2 px-3">
            <Heading>{{ props.project?.number }}</Heading>
            <p>Reference Number: {{ props.project?.reference_number }}</p>
            <p>Title: {{  props.project?.title }}</p>
            <p>Indicator: {{ props.project.indicator }}</p>
            <p>Cost: {{ props.project?.cost }}</p>
        </ProjectLayout>
    </div>
</template>

<script setup>
    const props = defineProps({
        project: Object
    })
</script>

<style lang="scss" scoped>

</style>