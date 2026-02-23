export function useApprovedPows(filterType) {
    const { api } = useAxios()
    const { transformValidationErrors } = useCustomError()
    const loading = ref(false)
    const rawApprovedPows = ref([])
    const filteredApprovedPows = ref([])
    const approvedPows = ref([])
    const error = ref(null)

    const isSubmitting = ref(false)
    const errors = ref({})

    const approvedPowForm = reactive({
        preengineering_status_id: '',
        file_link: '',
        remarks: '',
    })

    const submitApprovedPow = async ({ preEngineeringId, onSuccess } = {}) => {
        isSubmitting.value = true
        const formData = new FormData()

        formData.append('preengineering_status_id', preEngineeringId)
        formData.append('file_link', approvedPowForm.file_link)
        formData.append('remarks', approvedPowForm.remarks)

        try {
            const { data } = await api.post('/api/approved_pow', formData)

            if (onSuccess) onSuccess(data)
            return data
        } catch (err) {
            if (err.response) {
                transformValidationErrors(err.response)
            } else {
                console.log('Unexpected error: ', err)
            }
            throw err
        } finally {
            isSubmitting.value = false
        }
    }

    const fetchApprovedPows = async () => {
        loading.value = true
        try {
            const response = await api.get('/api/fetch_approved_pow')
            rawApprovedPows.value = response.data.data
            applyFilter()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
        
    }

    const applyFilter = async () => {
        const filter = filterType.value
        if (filter === 'preengineered') {
            filteredApprovedPows.value = rawApprovedPows.value.filter(p => p.preengineering.length > 0)
        } else {
            filteredApprovedPows.value = rawApprovedPows.value
        }

        approvedPows.value = filteredApprovedPows.value.map(item => ({
            ...item,
            number: item?.latest_funding?.number,
            reference_number: item?.latest_funding?.reference_number,
            title: item?.title || '',
            approved_cost: item?.latest_funding?.approved_cost || '0.00',
            date_submitted_lce: item?.latest_preengineering?.date_submitted_lce,
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
        }))
        console.log("Approved POWs", approvedPows.value)
    }

    return {
        loading,
        errors,
        approvedPows,
        approvedPowForm,
        fetchApprovedPows,
        applyFilter,
        submitApprovedPow,
        isSubmitting,
    }
}