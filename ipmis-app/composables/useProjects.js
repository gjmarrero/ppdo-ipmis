export function useProjects(filterType) {
    const route = useRoute()
    const { api } = useAxios()
    const { user, me } = useAuth()
    const { resetErrorBag, transformValidationErrors } = useCustomError()
    const rawProjects = ref([])
    const filteredProjects = ref([])
    const projects = ref([])
    const project = ref([])
    const loading = ref(false)
    const error = ref(null)

    const isSubmitting = ref(false)
    const errors = ref({})
    const projectForm = reactive({
        title: '',
        fundsource_id: '',
        project_type_id: '',
        cost: '',
        locations: [
            { municipality_id: '', barangay_id: '', sitio: '' }
        ]
    })
    const files = ref([]);
    const fileInput = ref(null)

    const projectProposals = ref([])

    const yearFunded = computed(() => route.query.yearFunded)

    const fetchProjects = async () => {
        loading.value = true
        try {
            const response = await api.get('/api/projects', {
                params: {
                    yearFunded: yearFunded.value
                }
            })
            rawProjects.value = response.data.data
            applyFilter()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const applyFilter = async () => {

        const filter = filterType?.value
        if (filter === 'assigned') {
            filteredProjects.value = rawProjects.value.filter(p => p.validation_assignment === 'Assigned')
        } else if (filter === 'unassigned') {
            filteredProjects.value = rawProjects.value.filter(p => p.validation_assignment === 'Unassigned')
        } else if (filter === 'validated') {
            filteredProjects.value = rawProjects.value.filter(p => p.validation_status === 'Validated')
        } else if (filter === 'unvalidated') {
            filteredProjects.value = rawProjects.value.filter(p => p.validation_status === 'Unvalidated')
        } else if (filter === 'validation_assignments') {
            filteredProjects.value = rawProjects.value.filter(p =>
                p.validation_status === 'Unvalidated' &&
                p.validation?.responsible_people?.some(
                    rp => rp.employee_id === user.value.employee_id
                )
            )
        }
        else {
            filteredProjects.value = rawProjects.value
        }
        console.log("raw projects", rawProjects.value)
        projects.value = filteredProjects.value.map(item => ({
            ...item,
            title: item?.title || '',
            fundsource_id: item?.latest_funding?.fundsource_id || item?.fundsource_id,
            fundsource: item?.latest_funding?.fundsource || item?.fundsource,
            cost: item?.latest_funding?.approved_cost || item?.cost,
            approved_cost: item?.latest_funding?.approved_cost,
            project_status: item?.status || '',
            funding_status: item?.status || '',
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
            validation_status: item?.validation_status || '',
            validation_assignment: item?.validation_assignment || '',
            locations_array: item?.locations || [],
            is_pamb: item?.validation?.data?.is_pamb || '',
            other_requirements: item?.project_other_requirements,
            number: item?.latest_funding?.number,
            reference_number: item?.latest_funding?.reference_number
        }))
    }

    const resetForm = () => {
        projectForm.title = ''
        projectForm.fundsource_id = ''
        projectForm.project_type_id = ''
        projectForm.cost = ''
        projectForm.locations = [{ id: '', municipality_id: '', barangay_id: '', sitio: '' }]
        files.value = []
        if (fileInput.value) fileInput.value.value = ''
    }

    const submitProject = async ({ mode = 'add', projectId = null, onSuccess } = {}) => {

        isSubmitting.value = true
        resetErrorBag()

        const formDataObject = new FormData()
        formDataObject.append('project_type_id', projectForm.project_type_id)
        formDataObject.append('fundsource_id', projectForm.fundsource_id)
        formDataObject.append('title', projectForm.title)
        const cleanedCost = projectForm.cost.replace(/,/g, "")
        formDataObject.append('cost', cleanedCost)
        formDataObject.append('status', 'proposal')
        formDataObject.append('input_type', 'proposal')

        projectForm.locations.forEach((location, index) => {
            if (location.id) {
                formDataObject.append(`locations[${index}][id]`, location.id)
            }
            formDataObject.append(`locations[${index}][municipality_id]`, location.municipality_id)
            formDataObject.append(`locations[${index}][barangay_id]`, location.barangay_id)
            formDataObject.append(`locations[${index}][sitio]`, location.sitio)
        })
        files.value.forEach(file => formDataObject.append('files[]', file))
        resetErrorBag()
        try {
            let url = '/api/project'
            let method = 'post'

            if (mode === 'edit' && projectId) {
                url = `/api/project/${projectId}?_method=PUT`
            }

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

            resetForm()

            if (onSuccess) onSuccess(data)

            return data
        } catch (err) {
            if (err.response) {
                transformValidationErrors(err.response)
            } else {
                console.log('Unexpected error', err)
            }
            throw err
        } finally {
            isSubmitting.value = false
        }
    }

    const fetchProject = async (id) => {
        if (!id) return
        loading.value = true
        try {
            const response = await api.get(`/api/project/${id}`)
            project.value = response.data.data
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const deleteProject = async (id) => {
        try {
            await useFetch(`/api/projects/${id}`, { method: 'DELETE' })
            projects.value = projects.value.filter(p => p.id !== id)
            if (project.value?.id === id) project.value = null
        } catch (err) {
            error.value = err
        }
    }

    const searchProjects = async (query) => {
        return await fetchProjects({ search: query })
    }

    function fetchProjectProposals() {
        api.get('/api/project_proposals').then(({ data }) => {
            projectProposals.value = data
        })
    }

    watch(filterType, () => {
        applyFilter()
    })


    return {
        projects,
        project,
        loading,
        error,
        projectForm,
        files,
        fileInput,
        isSubmitting,
        errors,
        projectProposals,
        fetchProjects,
        applyFilter,
        submitProject,
        resetForm,
        fetchProject,
        deleteProject,
        searchProjects,
        fetchProjectProposals
    }
}
