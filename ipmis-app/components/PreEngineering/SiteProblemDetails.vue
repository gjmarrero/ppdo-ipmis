<template>
    <div class="bg-cardbg border border-borderblackui p-4 rounded-lg shadow hover:shadow-md transition">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Site Problem</h3>
        <InfoItem label="Site Problem" :value="project?.latest_funding?.latest_site_problem?.problem_type" />
        <InfoItem label="Date Report Submitted" :value="project?.latest_funding?.latest_site_problem?.date_report_submitted" />
        <InfoItem label="Result" :value="project?.latest_funding?.latest_site_problem?.pdc_result" />
        <InfoItem label="Remarks" :value="project?.latest_funding?.latest_site_problem?.remarks" />
    </div>
</template>

<script setup>
const  props = defineProps({
    project: Object,
})

onMounted(() => {
    console.log("SP mounted", props.project)
})
</script>

<style lang="scss" scoped></style>