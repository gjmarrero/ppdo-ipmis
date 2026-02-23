export function useImplementations(filterType) {
    const { api } = useAxios()

    const loading = ref(true)
    const rawImplementations = ref([])
    const filteredImplementations = ref([])
    const implementations = ref([])
    const error = ref(null)

    const isSubmitting = ref(false)
    const errors = ref({})

    const fetchImplementations = async () => {
        loading.value = true
        try {
            const response = await api.get('/api/implementations')
            rawImplementations.value = response.data.data
            console.log("Raw", rawImplementations.value)
            applyFilter()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const applyFilter = async () => {
        const filter = filterType.value
        if (filter === 'received') {
            filteredImplementations.value = rawImplementations.value.filter(p => p.document_status === 'Received')
        } else if (filter === 'pending') {
            filteredImplementations.value = rawImplementations.value.filter(p => p.document_status === 'Pending')
        } else {
            filteredImplementations.value = rawImplementations.value
        }

        implementations.value = filteredImplementations.value.map(item => ({
            ...item,
            title: item?.latest_funding?.latest_title || item?.initial_title || '',
            number: item?.latest_funding?.number || '',
            reference_number: item?.latest_funding?.reference_number || '',
            approved_cost: item?.latest_funding?.approved_cost || '',
            date_approved_lce: item?.latest_funding?.latest_preengineering?.date_approved_lce || '',
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
            locations_array: item?.project?.locations || [],
            date_documents_received: !item?.latest_funding?.latest_implementation
                ? "Pending"
                : item?.latest_funding?.latest_implementation?.date_received,
            date_start_implementation: !item?.latest_funding?.latest_implementation
                ? "Pending"
                : item?.latest_funding?.latest_implementation?.date_start,

        }))
        console.log("Implementations", implementations.value)
    }

    const implementationForm = reactive({
        funded_project_id: '',
        date_received: '',
        employee_id: '',
        date_start: '',
    })

    const submitImplementation = async ({ mode = 'add', implementationId = null, fundedProjectId, onSuccess } = {}) => {
        isSubmitting.value = true
        errors.value = {}
        try {
            const formDataObject = new FormData()
            const excludeKeys = ['funded_project_id']

            if (fundedProjectId) {
                formDataObject.append('funded_project_id', fundedProjectId)
            }

            Object.keys(implementationForm).forEach(key => {
                if (!excludeKeys.includes(key)) {
                    formDataObject.append(key, implementationForm[key] ?? '')
                }
            })

            let url = '/api/implementation'
            let method = 'post'

            if (mode === 'edit' && implementationId) {
                url = `/api/implementation/${implementationId}?_method=PUT`
            }

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

            if (onSuccess) onSuccess(data)

            return data
        } catch (err) {
            if (err.response?.data?.errors) {
                errors.value = err.response.data.errors
            } else {
                console.error('Unexpected error', err)
            }
            throw err
        } finally {
            isSubmitting.value = false
        }
    }

    const resetForm = () => {
        Object.assign(implementationForm, {
            funded_project_id: '',
            date_received: '',
            employee_id: '',
            date_start: '',
        })
    }

    return {
        loading,
        implementations,
        error,
        fetchImplementations,
        applyFilter,
        implementationForm,
        resetForm,
        submitImplementation
    }
}