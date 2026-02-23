<template>
    <RightDrawer v-model:isOpen="isDrawerOpen" @drawerClosed="handleDrawerClose">
        <form @submit.prevent="addProject">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow mb-8">
                <FormGroup placeholder="Project number" v-model="projectForm.number" :errorMessage="errorBag.number"
                    name="project_number" label="Number" />
                <FormGroup placeholder="Project reference number" v-model="projectForm.reference_number"
                    :errorMessage="errorBag.reference_number" name="project_reference_number"
                    label="Reference Number" />
                <FormGroup placeholder="Project title" v-model="projectForm.title" :errorMessage="errorBag.title"
                    name="project_title" label="Title" />
                <FormGroup placeholder="Project indicator" v-model="projectForm.indicator"
                    :errorMessage="errorBag.indicator" name="project_indicator" label="Indicator" />
                <FormGroup placeholder="Project Cost" v-model="projectForm.cost" :errorMessage="errorBag.cost"
                    name="project_cost" label="Cost" />                
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow mb-8">
                <div>
                    <FormLabel>Municipality</FormLabel>
                    <FormCombobox :options="municipalities" placeholder="Select municipality" v-model="selectedMunicipality" />
                </div>
                
                <div>
                    <FormLabel>Barangay</FormLabel>
                    <FormCombobox :options="barangays" placeholder="Select barangay" v-model="selectedBarangay" :disabled="!selectedMunicipality" />
                </div>
            </div>
            
            <div>
                <Button type="submit" variant="secondary">Add</Button>
            </div>

        </form>
    </RightDrawer>
</template>

<script setup>
const isDrawerOpen = ref(true);

const props = defineProps({
    fundsource: Object
})

const { api } = useAxios()

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const projectForm = reactive({
    number: '',
    reference_number: '',
    title: '',
    indicator: '',
    cost: '',
    fundsource_id: props.fundsource?.id
})

const emit = defineEmits(['add', 'drawerClosed'])

function addProject() {
    resetErrorBag()
    projectForm.fundsource_id = props.fundsource.id
    api.post('/api/project', projectForm).then(({ data }) => {
        console.log('data', data.data)
        emit('add', data.data)
    }).catch(err => {
        transformValidationErrors(err.response)
    })
}

function handleDrawerClose() {
    emit('drawerClosed', true)
}

const options = ['Apple', 'Banana', 'Cherry', 'Date', 'Grape', 'Mango']

// Selected option
const selectedOption = ref('')

const municipalities = ref([])
const selectedMunicipality = ref(null)

const barangays = ref([])
const selectedBarangay = ref(null)

const fetchMunicipalities = async () => {
    const { data, error } = await useFetch('/api/municipalities', {
        baseURL: useRuntimeConfig().public.API_URL,
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("token"),
        },
        credentials: 'include'
    })
    if(!error.value){
        municipalities.value = data.value
    }
}

function fetchBarangays(municipalityId){
    api.get(`/api/municipalities/${municipalityId}/barangays`).then(({data}) => {
        barangays.value = data
    })
}

watch(selectedMunicipality, (newMunicipality) => {
    selectedBarangay.value = null
    if(newMunicipality){
        fetchBarangays(newMunicipality)
    }else{
        barangays.value = []
    }
})

fetchMunicipalities()

</script>

<style lang="scss" scoped></style>