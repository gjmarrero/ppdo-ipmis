export function usePreEngineerings(filterType) {
    const { api } = useAxios()
    const { user, me } = useAuth()
    const { resetErrorBag, transformValidationErrors } = useCustomError()
    const loading = ref(false)
    const rawPreEngineerings = ref([])
    const filteredPreEngineerings = ref([])
    const preEngineerings = ref([])
    const error = ref(null)

    const isSubmitting = ref(false)
    const isSubmittingQcp = ref(false)
    const isSubmittingReview = ref(false)
    const isSubmittingSiteProblem = ref(false)

    const errors = ref({})

    const preEngineeringForm = reactive({
        project_type_id: '',
        scope_of_work_id: '',
        employee_id: '',
        date_conducted: '',
        scopes: [
            { scope_of_work_id: '', description: '' }
        ],
        programmed_cost: '',
        date_prepared_pow: '',
        date_checked_pow: '',
        date_reviewed_pow: '',
        date_submitted_divhead: '',
        date_approved_pe: '',
        date_submitted_lce: '',
        date_approved_pe: '',
    })

    const qualityControlForm = reactive({
        date_received_by_qc: '',
        employee_id_qcp: '',
        date_qcp_prepared: '',
        date_qcp_reviewed: '',
    })

    const reviewForm = reactive({
        date_received_by_ape: '',
        date_reviewed: '',
        date_recommended_for_approval: '',
        date_submitted_to_lce: '',
    })

    const siteProblemForm = reactive({
        problem_type: '',
        date_report_prepared: '',
        date_report_checked: '',
        date_report_submitted: '',
        remarks: '',
        file: '',
        other_site_problem: '',
    })

    const siteProblemFiles = ref([])
    const siteProblemFileInput = ref(null)

    const files = ref([]);
    const fileInput = ref(null)

    const images = ref([])
    const imageInput = ref(null)

    const fetchPreEngineerings = async () => {
        loading.value = true
        try {
            const response = await api.get('/api/pre_engineerings')
            rawPreEngineerings.value = response.data.data
            applyFilter()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const applyFilter = async () => {
        const filter = filterType?.value
        if (filter === 'preengineered') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.date_conducted)
        } else if (filter === 'unpreengineered') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => !p.latest_funding?.latest_preengineering)
        } else if (filter === 'assigned') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.employee_id === user.value.employee_id &&
                p.latest_funding?.latest_preengineering?.date_submitted_lce == null)
        }
        else if (filter === 'for_pow') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => !p.latest_funding?.latest_preengineering)
        }
        else if (filter === 'for_qcp') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.date_reviewed_pow && !p.latest_funding?.latest_preengineering?.date_qcp_reviewed)
        }
        else if (filter === 'for_review') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.date_reviewed_pow && p.latest_funding?.latest_preengineering?.date_qcp_reviewed && !p.latest_funding?.latest_preengineering?.date_recommended_for_approval)
        }
        else if (filter === 'processed_pow') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.date_submitted_to_lce)
        }
        else if (filter === 'pow_assignments') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.employee_id === user.value.employee_id && !p.latest_funding?.latest_preengineering?.date_reviewed_pow)
        }
        else if (filter === 'qcp_assignments') {
            filteredPreEngineerings.value = rawPreEngineerings.value.filter(p => p.latest_funding?.latest_preengineering?.employee_id_qcp === user.value.employee_id && p.latest_funding?.latest_preengineering?.date_reviewed_pow && !p.latest_funding?.latest_preengineering?.date_qcp_reviewed)
        }
        else {
            filteredPreEngineerings.value = rawPreEngineerings.value
        }

        preEngineerings.value = filteredPreEngineerings.value.map(item => ({
            ...item,
            title: item.title || '',
            approved_cost: item?.latest_funding?.approved_cost || '',
            number: item?.latest_funding?.number,
            reference_number: item?.latest_funding?.reference_number,
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
            locations_array: item?.locations || [],
            preengineering_status: item?.latest_funding?.preengineering_status,
            latest_site_problem_type: item?.latest_funding?.latest_site_problem?.problem_type ?? '',
            latest_site_problem_pdc_result: item?.latest_funding?.latest_site_problem?.pdc_result ?? '',
            project_type: item?.project_type_array?.project_type,
            other_requirements: item?.project_other_requirements || '',
            is_pamb: item?.validation?.data?.is_pamb || '',
            qcp_status: item?.latest_funding?.latest_preengineering?.qcp_status ?? 'Pending',
            review_status: item?.latest_funding?.latest_preengineering?.review_status ?? 'Pending',
        }))
        console.log("Filtered and mapped", preEngineerings.value)
    }

    const resetForm = () => {
        Object.assign(preEngineeringForm, {
            project_type_id: '',
            employee_id: '',
            date_conducted: '',
            programmed_cost: '',
            date_prepared_pow: '',
            date_checked_pow: '',
            date_reviewed_pow: '',
            date_received_by_qcp: '',
            employee_id_qc: '',
            date_qcp_prepared: '',
            date_qcp_reviewed: '',
            date_submitted_divhead: '',
            date_approved_pe: '',
            date_submitted_lce: '',
            date_approved_pe: '',
            date_approved_lce: ''
        })
        images.value = []
    }

    const resetQualityControlForm = () => {
        Object.assign(qualityControlForm, {
            date_received_by_qcp: '',
            employee_id_qc: '',
            date_qcp_prepared: '',
            date_qcp_reviewed: '',
        })
    }

    const resetSiteProblemForm = () => {
        Object.assign(siteProblemForm, {
            problem_type: '',
            date_report: '',
            date_report_checked: '',
            date_report_submitted: '',
            remarks: '',
        })
        files.value = []
    }

    const submitSiteProblem = async ({ fundedProjectId, onSuccess } = {}) => {
        isSubmittingSiteProblem.value = true
        errors.value = {}

        try {
            const formDataObject = new FormData()
            if (fundedProjectId) {
                formDataObject.append('funded_project_id', fundedProjectId)
            }

            const finalProblemType =
                siteProblemForm.problem_type === 'Others'
                    ? siteProblemForm.other_site_problem
                    : siteProblemForm.problem_type

            Object.keys(siteProblemForm).forEach(key => {
                if (key === 'problem_type') {
                    formDataObject.append('problem_type', finalProblemType ?? '')
                } else if (key === 'other_site_problem') {
                    formDataObject.append(key, siteProblemForm[key] ?? '')
                } else {
                    formDataObject.append(key, siteProblemForm[key] ?? '')
                }
            })

            let url = '/api/site_problem'
            let method = 'post'

            console.log([...formDataObject.entries()])
            console.log("FPId", fundedProjectId)

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

            resetSiteProblemForm

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
            isSubmittingSiteProblem.value = false
        }
    }

    const submitPreEngineering = async ({ mode = 'add', preEngineeringId = null, fundedProjectId, onSuccess } = {}) => {
        resetErrorBag()
        isSubmitting.value = true
        errors.value = {}
        try {
            const formDataObject = new FormData()
            const excludeKeys = ['funded_project_id', 'programmed_cost', 'scopes']

            if (fundedProjectId) {
                formDataObject.append('funded_project_id', fundedProjectId)
            }
            Object.keys(preEngineeringForm).forEach(key => {
                if (!excludeKeys.includes(key)) {
                    formDataObject.append(key, preEngineeringForm[key] ?? '')
                }
            })

            preEngineeringForm.scopes.forEach((scope, index) => {
                if (scope.id) {
                    formDataObject.append(`scopes[${index}][id]`, scope.id)
                }
                formDataObject.append(`scopes[${index}][scope_of_work_id]`, scope.scope_of_work_id)
                formDataObject.append(`scopes[${index}][description]`, scope.description)
                formDataObject.append(`scopes[${index}][remarks]`, scope.remarks)
            })

            const cleanedProgrammedCost = preEngineeringForm.programmed_cost.replace(/,/g, "")
            formDataObject.append('programmed_cost', cleanedProgrammedCost)
            images.value.forEach((img, index) => {
                formDataObject.append(`images[${index}][file]`, img.file)
                formDataObject.append(`images[${index}][lat]`, img.lat)
                formDataObject.append(`images[${index}][long]`, img.long)
            })

            let url = '/api/preengineering'
            let method = 'post'

            if (mode === 'edit' && preEngineeringId) {
                url = `/api/preengineering/${preEngineeringId}?_method=PUT`
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

    const submitQualityControl = async ({ preEngineeringId = null, onSuccess }) => {
        isSubmittingQcp.value = true
        errors.value = {}

        try {
            const formDataObject = new FormData()
            Object.keys(qualityControlForm).forEach(key => {
                formDataObject.append(key, qualityControlForm[key] ?? '')
            })

            let url = `/api/preengineering/qcp_status/${preEngineeringId}`
            let method = 'put'

            console.log(["Form Content", ...formDataObject.entries()])

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
            isSubmittingQcp.value = false
        }
    }

    const submitReviewStatus = async ({preEngineeringId = null, onSuccess }) => {
        console.log("Submitting review status for PE ID:", reviewForm, preEngineeringId)
        resetErrorBag()
        isSubmittingReview.value = true
        errors.value = {}
        try {
            const formDataObject = new FormData()
            Object.keys(reviewForm).forEach(key => {
                formDataObject.append(key, reviewForm[key] ?? '')
            })

            let url = `/api/preengineering/review/${preEngineeringId}`
            let method = 'put'

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

            if (onSuccess) onSuccess(data)

            return data
        } catch (err) {
            if (err.response) {
                transformValidationErrors(err.response)
            } else {
                console.error('Unexpected error', err)
            }
            throw err
        } finally {
            isSubmittingReview.value = false
        }
    }

    watch(filterType, () => {
        applyFilter()
    })


    return {
        loading,
        isSubmitting,
        isSubmittingQcp,
        isSubmittingReview,
        isSubmittingSiteProblem,
        errors,
        preEngineeringForm,
        qualityControlForm,
        reviewForm,
        siteProblemForm,
        images,
        imageInput,
        files,
        fileInput,
        siteProblemFiles,
        siteProblemFileInput,
        preEngineerings,
        submitPreEngineering,
        submitSiteProblem,
        submitQualityControl,
        submitReviewStatus,
        resetForm,
        resetQualityControlForm,
        resetSiteProblemForm,
        fetchPreEngineerings,
        applyFilter
    }
}