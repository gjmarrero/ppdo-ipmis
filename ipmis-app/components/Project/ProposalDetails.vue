<template>
    <TopDrawer>
        <template #header>
            <div class="custom-header flex items-center">
                <h2>Proposal Details for {{ project }}</h2>
            </div>
        </template>
    </TopDrawer>

</template>

<script setup>
import TopDrawer from '../TopDrawer.vue';

const props = defineProps({
    project: Object,
})


onMounted(() => {
    console.log('Proposal',props.project)
})

</script>

<style lang="scss" scoped></style>