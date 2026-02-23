export function usePowForApproval(filterType) {
    const { api } = useAxios()
    const { transformValidationErrors } = useCustomError()
    const loading = ref(false)
    const rawForApprovalPows = ref([])
    const filteredForApprovalPows = ref([])
    const forApprovalPows = ref([])
    const error = ref(null)

    const isSubmitting = ref(false)
    const errors = ref({})

    const forApprovalPowForm = reactive({
        preengineering_id: '',
        approval_status: '',
        date_approved_lce: '',
    })

    const submitApprovedPow = async ({ preEngineeringId, onSuccess } = {}) => {
        isSubmitting.value = true
        const formData = new FormData()

        formData.append('preengineering_id', preEngineeringId)
        formData.append('date_approved_lce', forApprovalPowForm.date_approved_lce)

        try {
            const { data } = await api.post('/api/preengineering/approve_pow', formData)

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

    const fetchForApprovalPows = async () => {
        loading.value = true
        try {
            const response = await api.get('/api/pow_for_approval')
            rawForApprovalPows.value = response.data.data
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
            filteredForApprovalPows.value = rawForApprovalPows.value.filter(p => p.preengineering.length > 0)
        } else {
            filteredForApprovalPows.value = rawForApprovalPows.value
        }

        forApprovalPows.value = filteredForApprovalPows.value.map(item => ({
            ...item,
            number: item?.latest_funding?.number,
            reference_number: item?.latest_funding?.reference_number,
            title: item?.title || '',
            approved_cost: item?.latest_funding?.approved_cost || '0.00',
            date_submitted_lce: item?.latest_funding?.latest_preengineering?.date_submitted_lce,
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
        }))
        console.log(forApprovalPows.value)
    }

    return {
        loading,
        errors,
        forApprovalPows,
        forApprovalPowForm,
        fetchForApprovalPows,
        applyFilter,
        submitApprovedPow,
        isSubmitting,
    }
}