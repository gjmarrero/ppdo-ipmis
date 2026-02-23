<template>
    <MetaTags title="Reports" />
    <div class="mt-4">
        <Heading as="h3">Projects Within Protected Area</Heading>
    </div>
    <div class="p-4">
        <div class="flex justify-center">
            <form @submit.prevent="handleFormSubmission">
                <div class="flex flex-row gap-2 items-end">
                    <FormGroup :errorMessage="errorBag.yearFunded" label="Year Funded" name="yearFunded" required
                        labelFor="yearFunded" class="flex-1">
                        <FormCombobox v-model="filterForm.yearFunded" id="yearFunded" :options="years" placeholder="Select Year" />
                    </FormGroup>

                    <div class="flex-1">
                        <FormLabel>Protected Area</FormLabel>
                        <FormCombobox v-model="filterForm.pambArea" :options="pambAreas" placeholder="Select PA" />
                    </div>
                    <div class="flex-1">
                        <FormLabel>PAMB Clearance Status</FormLabel>
                        <FormCombobox v-model="filterForm.pambClearanceStatus" :options="pambClearanceStatus"
                            placeholder="Select" />
                    </div>
                    <Button type="submit" variant="newprimary">Submit</Button>
                    <Button @click="handleDownloadReport" variant="newprimary">Download</Button>
                </div>
            </form>
        </div>
        <div>
            <DataTable :columns="columns" :data="pambProjects" />
        </div>
    </div>
</template>

<script setup>
const { api } = useAxios()
const { errorBag } = useCustomError()

import { getProjectColumns } from '../columns'

const { projects, fetchProjects } = useProjects()

const loading = ref(true)
const error = ref(null)

const currentYear = new Date().getFullYear()

const years = Array.from(
  { length: 20 },
  (_, i) => {
    const year = currentYear - i
    return {
      id: year,
      name: String(year),
    }
  }
)

const filterForm = reactive({
    'yearFunded': '',
    'pambArea': '',
    'pambClearanceStatus': '',
})
const columns = shallowRef(getProjectColumns())

const pambProjects = ref([])

const fetchFundedProjectsWithinPamb = async () => {
    loading.value = true
    try {
        const response = await api.get('/api/pambProjects')
        pambProjects.value = response.data.data.map(p => ({ ...p }))
    } catch (err) {
        error.value = err
    } finally {
        loading.value = false
    }
}


const handleFormSubmission = async () => {
    console.log("submitting")
    const { data } = await api.get('/api/pambProjects', {
        params: filterForm,
    })

    pambProjects.value = data.data
}

const generateReport = async () => {

    const response = await api.get('api/reports/projects', {
        responseType: 'blob'
    })

    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'report.docx')
    document.body.appendChild(link)
    link.click()
    link.remove()

}

const pambAreas = ref([])

const fetchPambAreas = () => {
    api.get('/api/e_c_pamb_types').then(({ data }) => {
        pambAreas.value = data
    })
}

const pambClearanceStatus = [
    { id: 'Issued', name: 'Issued' },
    { id: 'Pending', name: 'Pending' },
    { id: 'For application', name: 'For Application' }
]

onMounted(() => {
    fetchFundedProjectsWithinPamb()
    fetchProjects()
    fetchPambAreas()
})



</script>

<style lang="scss" scoped></style>