<template>
  <div>
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button variant="ghost" class="w-8 h-8 p-0">
          <span class="sr-only">Open menu</span>
          <MoreHorizontal class="w-4 h-4" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        <DropdownMenuLabel>Actions</DropdownMenuLabel>

        <DropdownMenuItem @click="handleView" v-if="$route.path !== '/dashboard/for_approval_pows' && $route.path !=='/dashboard/approved_pows'">View</DropdownMenuItem>
        <DropdownMenuItem v-if="$route.path === '/dashboard/projects' || $route.path === '/dashboard/funded_projects'"
          @click="handleEdit">Edit</DropdownMenuItem>
        <DropdownMenuItem
          v-if="($route.path === '/dashboard/preengineerings/program_of_work' || $route.path === '/dashboard/funded_projects') && (project.latest_site_problem_type === null || project.latest_site_problem_type === '' || project.latest_site_problem_pdc_result === 'Resolved')"
          @click="handleSiteProblem">Site Problem</DropdownMenuItem>
        <DropdownMenuItem
          v-if="$route.path === '/dashboard/funded_projects' && ((project.latest_site_problem_type !== null || project.latest_site_problem_type !== '') && project.latest_site_problem_pdc_result !== 'Resolved')"
          @click="handlePdcResult">PDC Result</DropdownMenuItem>
        <DropdownMenuItem v-if="($route.path === '/dashboard/for_approval_pows')" @click="handleApprovePow">Approve</DropdownMenuItem>

        <DropdownMenuItem v-if="($route.path === '/dashboard/approved_pows')" @click="handleApprovedPow">Set File Link</DropdownMenuItem>
        
        <!-- <DropdownMenuItem @click="handleSave">Save</DropdownMenuItem> -->
        <!-- <DropdownMenuItem @click="copy(project.id)"> -->

        <!-- <DropdownMenuItem v-if="project.status === 'funded'" @click="openProject(project.id)">View</DropdownMenuItem>
        <DropdownMenuItem v-if="project.validation_assignment === 'Unassigned'" @click="handleEdit">Assign
        </DropdownMenuItem>
        <DropdownMenuItem
          v-if="project.validation_status === 'Unvalidated' && project.validation_assignment === 'Assigned'"
          @click="handleEdit">Validate</DropdownMenuItem> -->

        <DropdownMenuSeparator />
        <DropdownMenuSeparator />
      </DropdownMenuContent>
    </DropdownMenu>

  </div>


</template>
<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { MoreHorizontal } from 'lucide-vue-next'
import Project from './Project/Form/Project.vue';

const route = useRoute()
const router = useRouter()

const props = defineProps<{
  project: {
    id: string,
    status: string,
    title: string,
    validation_status: string,
    validation_assignment: string,
    input_type: string,
    latest_site_problem_type: string,
    latest_site_problem_pdc_result: string,
    latest_funding: {
      id: string,
    },
    default: null
  },
}>()

const view_type = ref('validation')

const emit = defineEmits(['action', 'editProject', 'refresh', 'openDetail', 'siteProblem', 'pdcResult', 'approvePow', 'setFileLink'])

const handleEdit = () => {
  emit('editProject')
}

const handleView = () => {
  if (route.path.includes('/dashboard/funded_projects')) {
    console.log("table props", props.project)
    navigateTo({ path: `/dashboard/funded_projects/${props.project.latest_funding.id}` })
  } else {
    emit('openDetail')
  }
}
const isValidationDrawerOpen = ref(false)

const handleSiteProblem = () => {
  emit('siteProblem')
}

const handlePdcResult = () => {
  emit('pdcResult')
}

const handleApprovePow = () => {
  emit('approvePow')
}

const handleApprovedPow = () => {
  emit('setFileLink')
}

function openProject(id: string) {
  navigateTo({ path: `/dashboard/funded_projects/${id}` })
}


const mode = ref()

function openValidation(project: Object) {
  if (props.project.validation_assignment === 'Assigned' && props.project.validation_status === 'Unvalidated') {
    mode.value = 'edit'
  } else if (props.project.validation_status === 'Unvalidated') {
    mode.value = 'add'
  }
  isValidationDrawerOpen.value = true
}

const handleFormSubmission = () => {
  isValidationDrawerOpen.value = false

}

const triggerAction = (action: string) => {
  emit('action', action, props.project.id)
}

const isFundedValidation = ref(false)

function validateFunded(id: string) {
  isFundedValidation.value = true
}
</script>
