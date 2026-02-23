<template>
    <div>
        <div class="flex flex-row justify-end">
            <Button @click="toggleDrawer" variant="newprimary" class="px-4 py-2 w-40">
                Add
            </Button>
        </div>
        <RightDrawer v-model:isOpen="isDrawerOpen" class="w-[600px]">

            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Add to project-beneficiary pivot</h2>
                </div>
            </template>

            <form class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow" @submit.prevent="addProjectBeneficiary">
                <div class="mb-8">
                    <FormLabel>Project Type</FormLabel>
                    <FormCombobox :options="projecttypes" placeholder="Select project type"
                        v-model="projectBeneficiaryForm.project_type_id" :errorMessage="errorBag.project_type_id" />
                </div>
                <div class="mb-8">
                    <FormLabel>Beneficiary Type</FormLabel>
                    <FormCombobox :options="beneficiarytypes" placeholder="Select beneficiary type"
                        v-model="projectBeneficiaryForm.beneficiary_type_id"
                        :errorMessage="errorBag.beneficiary_type_id" />
                </div>
                <div class="flex flex-row justify-end">
                    <Button type="submit" class="py-2 w-40" variant="newprimary">Save</Button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>

const isDrawerOpen = ref(false);

const toggleDrawer = () => {
    isDrawerOpen.value = !isDrawerOpen.value;
};

const projectBeneficiaryForm = reactive({
    project_type_id: '',
    beneficiary_type_id: ''
})

const clearForm = () => {
    projectBeneficiaryForm.project_type_id = ''
    projectBeneficiaryForm.beneficiary_type_id = ''
}


const { api } = useAxios()
const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const emit = defineEmits(['add'])

function addProjectBeneficiary() {
    resetErrorBag()
    console.log("Form:", projectBeneficiaryForm)
    api.post('/api/project_beneficiary', projectBeneficiaryForm).then(({ data }) => {
        clearForm()
        isDrawerOpen.value = false
        emit('add', data)
    }).catch((error) => {
        transformValidationErrors(error.response)
    })
}

const projecttypes = ref([])

const fetchProjectTypes = () => {
    api.get('/api/fetchProjectTypes').then(({ data }) => {
        projecttypes.value = data
    })
}

const beneficiarytypes = ref([])

const fetchBeneficiaryTypes = () => {
    api.get('/api/fetchBeneficiaryTypes').then(({ data }) => {
        beneficiarytypes.value = data
    })
}

onMounted(() => {
    fetchProjectTypes()
    fetchBeneficiaryTypes()
})
</script>