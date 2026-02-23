<template>
  <div class="space-y-4">
    <!-- Header -->
    <h2 class="font-mono text-lg text-center">Project Summary</h2>

    <!-- Main Card -->
    <div class="p-4 rounded-lg shadow hover:shadow-md transition space-y-4 bg-cardbg border border-borderblackui">

      <!-- Basic Info Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <InfoItem label="Title" :value="project.title"
          :subValue="project.latest_title ? project.initial_title : null" />
        <InfoItem label="Cost" :value="formatCurrency(project.approved_cost ?? project.cost)" />
        <InfoItem label="Location" :value="project.locations" />
        <InfoItem label="Project Type" :value="project.project_type ?? project?.latest_funding?.latest_preengineering?.project_type" />
        <InfoItem label="Number" :value="project.number" />
        <InfoItem label="Reference Number" :value="project.reference_number" />
      </div>

      <!-- <div v-if="project?.validation?.other_requirements?.length" class="bg-cardbg border border-borderblackui p-3 rounded-lg space-y-1">
        <span class="text-sm text-muted-foreground font-semibold">Other Requirements:</span>
        <div class="flex flex-wrap gap-2">
          <span v-for="requirement in project.validation.other_requirements" :key="requirement.id"
            :class="otherReqStatusClass(requirement.status)">
            {{ toProperCase(requirement.requirement_type) }}
            <span v-if="requirement.requirement_type==='pamb_clearance'">({{ requirement.pamb_area }})</span>
          </span>
        </div>
      </div> -->

      <div v-if="project?.project_other_requirements?.length" class="bg-cardbg border border-borderblackui p-3 rounded-lg space-y-1">
        <span class="text-sm text-muted-foreground font-semibold">Other Requirements:</span>
        <div class="flex flex-wrap gap-2">
          <span v-for="requirement in project.project_other_requirements" :key="requirement.id"
            :class="otherReqStatusClass(requirement.status)">
            {{ toProperCase(requirement.requirement_type) }}
            <span v-if="requirement.requirement_type === 'pamb_clearance'">({{ requirement.pamb_area }})</span>
          </span>
        </div>
      </div>

    </div>
    <div class="flex justify-end">
      <Button variant="newprimary" @click="manageOtherRequirements" class="flex items-center gap-2">
        <Blocks /> Manage Other Requirements
      </Button>
    </div>
  </div>
</template>

<script setup>
import { Blocks } from 'lucide-vue-next';
import InfoItem from '../InfoItem.vue';

const props = defineProps({
  project: Object
})

const emit = defineEmits(['manage-other-requirements'])

function toProperCase(str) {
  return str
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

const otherReqStatusClass = (status) => {
  if (status === 'Approved') {
    return 'bg-badgesuccess text-badgesuccesstext p-2'
  }
  if (status === 'Denied') {
    return 'bg-badgedanger text-badgedangertext p-2'
  }

  return 'bg-badgewarning text-badgewarningtext p-2'
}

const manageOtherRequirements = () => {
  emit('manage-other-requirements', props.project)
}

const formatCurrency = (amount) => {
  if (!amount && amount !== 0) return 'No data';
  return Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<style scoped lang="scss">
/* Optional: tweak spacing or colors if needed */
</style>
