export function useEnvironmentalClearances(filterType) {
    const { api } = useAxios()
    const { user, me } = useAuth()
    const { transformValidationErrors, resetErrorBag } = useCustomError()
    const loading = ref(false)
    const rawEnvironmentalClearances = ref([])
    const filteredEnvironmentalClearances = ref([])
    const environmentalClearances = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)
    const isSubmittingCmr = ref(false)
    const errors = ref({})

    const fetchEnvironmentalClearances = async () => {
        loading.value = true
        try {
            const response = await api.get('api/environmental_clearances')
            rawEnvironmentalClearances.value = response.data.data
            console.log("Raw", rawEnvironmentalClearances.value)
            applyFilter()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const applyFilter = async () => {

        const filter = filterType.value
        console.log("Raw EC", rawEnvironmentalClearances.value)
        if (filter === 'clearance_issued') {
            filteredEnvironmentalClearances.value = rawEnvironmentalClearances.value.filter(p => p.latest_funding?.latest_environmental_clearance?.environmental_clearance_status === 'Clearance Issued')
        } else if (filter === 'on_process') {
            filteredEnvironmentalClearances.value = rawEnvironmentalClearances.value.filter(p => p.latest_funding?.latest_environmental_clearance?.environmental_clearance_status === 'On Process')
        } else if (filter === 'for_application') {
            filteredEnvironmentalClearances.value = rawEnvironmentalClearances.value.filter(p => !p.latest_funding?.latest_environmental_clearance)
        } else if (filter === 'for_processing') {
            filteredEnvironmentalClearances.value = rawEnvironmentalClearances.value.filter(p => p.latest_funding?.latest_preengineering?.approved_pow && !p.latest_funding?.latest_environmental_clearance)
        } else if (filter === 'ec_assignments') {
            console.log("Assigned", user.value.employee_id)
            filteredEnvironmentalClearances.value = rawEnvironmentalClearances.value.filter(p => !p.latest_funding?.latest_environmental_clearance?.date_issued && p.latest_funding?.latest_environmental_clearance?.employee_id === user.value.employee_id)
        } else {
            filteredEnvironmentalClearances.value = rawEnvironmentalClearances.value
        }

        environmentalClearances.value = filteredEnvironmentalClearances.value.map(item => ({
            ...item,
            title: item?.title || '',
            approved_cost: item?.latest_funding?.approved_cost || '',
            number: item?.latest_funding?.number || '',
            reference_number: item?.latest_funding?.reference_number || '',
            locations_array: item?.project?.locations || [],
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
            certificate_type: item?.latest_funding?.latest_environmental_clearance?.certificate_type || 'For Identification',
            environmental_clearance_status: item?.latest_funding?.latest_environmental_clearance?.environmental_clearance_status || 'For Application',
            ec_status: item?.latest_funding?.latest_environmental_clearance?.environmental_clearance_status || 'For Application',
            other_requirements: item?.project_other_requirements || [],
            is_pamb: item?.validation?.data?.is_pamb || '',
            is_pow_ready: !!item?.latest_funding?.latest_preengineering?.approved_pow
        }))
    }

    const environmentalClearanceForm = reactive({
        e_c_certificate_type_id: '',
        date_application: '',
        date_issued: '',
        employee_id: '',
        status: '',
        is_transmitted_peo: 0,
        date_transmitted: '',
        remarks: '',
        files: [
            { file: '', type: 'ecc_cnc' }
        ],
    })

    const cmrForm = reactive({
        date_prepared: '',
        date_submitted: '',
        file: '',
        remarks: '',
    })

    const files = ref([])
    const cmr_files = ref([])

    const fileInput = ref(null)
    const cmrFileInput = ref(null)

    const isTransmitted = computed({
        get: () => environmentalClearanceForm.is_transmitted_peo === 1,
        set: (val) => {
            environmentalClearanceForm.is_transmitted_peo = val ? 1 : 0
        },
    })

    const updateOtherRequirementsForm = ref([])

    const initRequirementsForm = (requirements = [], selectedTypes = []) => {
        const list = []
        requirements.forEach(req => {
            list.push({
                id: req.id,
                requirement_type: req.requirement_type,
                pamb_type_id: req.pamb_type_id || '',
                date_applied: req.date_applied || '',
                date_issued: req.date_issued || '',
                status: req.status || '',
                pamb_area: req.pamb_area || '',
                employee_id: req.employee_id || '',
                remarks: req.remarks || '',
                files: [],
                existing_files: req.files || [],
            })
        })

        selectedTypes.forEach(type => {
            const exists = list.some(r => r.requirement_type === type)
            if (!exists) {
                list.push({
                    id: null,
                    requirement_type: type,
                    pamb_type_id: '',
                    date_applied: '',
                    date_issued: '',
                    status: '',
                    employee_id: '',
                    remarks: '',
                    files: [],
                    existing_files: req.files || [],
                })
            }
        })

        updateOtherRequirementsForm.value = list
    }

    const submitCmr = async ({ mode = 'add', environmentalClearanceId = null, environmentalClearanceCmrId = null, onSuccess } = {}) => {
        resetErrorBag()
        isSubmittingCmr.value = true
        errors.value = {}

        try {
            const formDataObject = new FormData()

            if (environmentalClearanceId) {
                formDataObject.append('environmental_clearance_id', environmentalClearanceId)
            }

            Object.keys(cmrForm).forEach(key => {
                formDataObject.append(key, cmrForm[key] ?? '')
            })

            if (cmr_files.value.length) {
                formDataObject.append('file', cmr_files.value[0])
            }


            let url = `/api/environmental_clearance/cmr/${environmentalClearanceId}`
            let method = 'post'

            if (mode === 'edit' && environmentalClearanceCmrId) {
                url = `/api/environmental_clearance/update_cmr/${environmentalClearanceCmrId}`
            }

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

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
            isSubmittingCmr.value = false
        }
    }

    const submitEnvironmentalClearance = async ({ mode = 'add', environmentalClearanceId = null, fundedProjectId, onSuccess } = {}) => {
        resetErrorBag()
        isSubmitting.value = true
        errors.value = {}

        try {
            const formDataObject = new FormData()
            const excludeKeys = ['funded_project_id', 'files', 'is_transmitted_peo']

            if (fundedProjectId) {
                formDataObject.append('funded_project_id', fundedProjectId)
            }

            formDataObject.append('is_transmitted_peo', isTransmitted.value ? 1 : 0)

            Object.keys(environmentalClearanceForm).forEach(key => {
                if (!excludeKeys.includes(key)) {
                    formDataObject.append(key, environmentalClearanceForm[key] ?? '')
                }
            })
            const allFiles = [
                ...files.value.map(f => ({ file: f, type: 'ecc_cnc' }))
            ]

            allFiles.forEach(({ file, type }) => {
                formDataObject.append('files[]', file)
                formDataObject.append('file_types[]', type)
            })

            let url = '/api/environmental_clearance'
            let method = 'post'

            if (mode === 'edit' && environmentalClearanceId) {
                url = `/api/environmental_clearance/${environmentalClearanceId}?_method=PUT`
            }

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

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

    const submitUpdateOtherRequirements = async ({ onSuccess, validationId = null, projectId = null } = {}) => {
        resetErrorBag()
        errors.value = {}
        console.log("Form value", updateOtherRequirementsForm.value)
        try {
            const formData = new FormData()
            updateOtherRequirementsForm.value.forEach((item, index) => {
                formData.append(`requirements[${index}][id]`, item.id || '')
                formData.append(`requirements[${index}][requirement_type]`, item.requirement_type)
                formData.append(`requirements[${index}][pamb_type_id]`, item.pamb_type_id || '')
                formData.append(`requirements[${index}][date_applied]`, item.date_applied)
                formData.append(`requirements[${index}][date_issued]`, item.date_issued)
                formData.append(`requirements[${index}][status]`, item.status)
                formData.append(`requirements[${index}][remarks]`, item.remarks)
                formData.append(`requirements[${index}][employee_id]`, item.employee_id)
                formData.append(`requirements[${index}][project_id]`, projectId)
                formData.append(`requirements[${index}][validation_id]`, validationId)

                if (item.files && item.files.length) {
                    item.files.forEach(file => {
                        formData.append(`requirements[${index}][files][]`, file)
                        console.log("what's appended", file)
                    })
                }
            })

            formData.append('_method', 'PUT')

            for (let pair of formData.entries()) {
                console.log("Test", pair[0], pair[1] instanceof File ? pair[1].name : pair[1])
            }

            const response = await api.post(`/api/update_other_requirements`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })

            if (onSuccess) onSuccess(response.data)

            return response.data
        } catch (err) {
            if (err.response) {
                transformValidationErrors(err.response)
            } else {
                console.log('Unexpected error', err)
            }
            throw err
        }
    }

    const resetForm = () => {
        Object.assign(environmentalClearanceForm, {
            e_c_certificate_type_id: '',
            date_application: '',
            date_issued: '',
            employee_id: '',
            status: '',
            is_transmitted_peo: 0,
            date_transmitted: '',
            remarks: '',
            files: [
                { file: '' }
            ],
        })
        files.value = []
    }

    return {
        loading,
        environmentalClearances,
        errors,
        environmentalClearanceForm,
        cmrForm,
        updateOtherRequirementsForm,
        initRequirementsForm,
        files,
        cmr_files,
        cmrFileInput,
        fileInput,
        isTransmitted,
        submitEnvironmentalClearance,
        submitUpdateOtherRequirements,
        submitCmr,
        resetForm,
        fetchEnvironmentalClearances,
        applyFilter,
        isSubmitting,
        isSubmittingCmr,
    }

}