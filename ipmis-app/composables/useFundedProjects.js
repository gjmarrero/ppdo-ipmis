import { formatDate } from "@vueuse/core"

export function useFundedProjects() {
    const { api } = useAxios()
    const route = useRoute()
    const { resetErrorBag, transformValidationErrors } = useCustomError()

    const loading = ref(false)
    const rawFundedProjects = ref([])
    const fundedProjects = ref([])
    const filteredFundedProjects = ref([])
    const error = ref([])
    const filterType = ref(route.query.filter || 'all')
    const fundedProject = ref([])

    const isSubmitting = ref(false)

    const fundedProjectForm = reactive({
        selectedProject: '',
        title: '',
        project_id: '',
        fundsource_id: '',
        number: '',
        reference_number: '',
        approved_cost: '',
        has_supplemental_budget: 0,
        sb_number: '',
        is_admin: 0,
        year_funded: '',
        locations: [
            { municipality_id: null, barangay_id: null, sitio: '', barangays: [] }
        ]
    })

    const pdcResultForm = reactive({
        pdc_result: '',
        archive_id: '',
        new_project_title: '',
        additional_fund: '',
        fundsource_id: '',
        remarks: '',
    })

    const pdcFiles = ref([])
    const pdcFileInput = ref(null)
    const sbResFiles = ref([])
    const sbResFileInput = ref(null)

    const fetchFundedProjects = async () => {
        loading.value = true
        try {
            const response = await api.get('/api/funded_projects')
            rawFundedProjects.value = response.data.data
            applyFilter()
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const applyFilter = () => {
        console.log("Raw funded projects", rawFundedProjects.value)
        const filter = filterType.value
        if (filter === 'validated') {
            filteredFundedProjects.value = rawFundedProjects.value.filter(p => p.validation_status === 'Validated')
        } else if (filter === 'programmed') {
            filteredFundedProjects.value = rawFundedProjects.value.filter(p => p.latest_funding?.latest_preengineering?.date_submitted_to_lce)
        } else if (typeof filter === 'string' && filter.match(/^[0-9a-fA-F-]{36}$/)) {
            filteredFundedProjects.value = rawFundedProjects.value.filter(
                p => p.project?.fundsource?.id === filter
            )
        } else {
            filteredFundedProjects.value = rawFundedProjects.value
        }

        fundedProjects.value = filteredFundedProjects.value.map(item => ({
            ...item,
            number: item?.latest_funding?.number,
            reference_number: item?.latest_funding?.reference_number,
            title: item.title || '',
            status: item.project?.status || '',
            approved_cost: item?.latest_funding?.approved_cost || '',
            locations_array: item?.locations || [],
            locations: item?.locations?.map(loc => {
                const parts = []
                if (loc.sitio) parts.push(loc.sitio)
                if (loc.barangay) parts.push(loc.barangay)
                if (loc.municipality) parts.push(loc.municipality)
                return parts.join(', ')
            }).join('; ') || 'No location',
            fundsource: item?.latest_funding?.fundsource,
            remarks: item?.latest_funding?.remarks,
            // site_problems: item?.latest_funding?.latest_site_problem?.map(problem => problem.problem_type || ''),
            latest_site_problem_type: item?.latest_funding?.latest_site_problem?.problem_type ?? null,
            latest_site_problem_pdc_result: item?.latest_funding?.latest_site_problem?.pdc_result ?? null,
            initial_title: item?.project?.title,
            latest_title: item?.project?.latest_title
        }))
    }

    const fetchFundedProject = async () => {
        const slug = route.params.slug
        loading.value = true
        try {
            const { data } = await api.get(`/api/funded_project/${slug}`)
            fundedProject.value = data.data
        } catch (error) {
            console.error('Failed to fetch funded project:', error)

            toast({
                title: 'Error',
                description: 'Failed to load project. Please try again.',
                variant: 'destructive'
            })
            return navigateTo('/dashboard')
        } finally {
            loading.value = false
        }

    }

    const hasSupplementalBudget = computed({
        get: () => fundedProjectForm.has_supplemental_budget === 1,
        set: (val) => {
            fundedProjectForm.has_supplemental_budget = val ? 1 : 0
        },
    })

    const isAdmin = computed({
        get: () => fundedProjectForm.is_admin === 1,
        set: (val) => {
            fundedProjectForm.is_admin = val ? 1 : 0
        },
    })

    const submitPdcResult = async ({ siteProblemId = null, onSuccess } = {}) => {

        const formData = new FormData()

        formData.append('pdc_result', pdcResultForm.pdc_result)
        formData.append('archive_id', pdcResultForm.archive_id)
        formData.append('new_project_title', pdcResultForm.new_project_title)
        const cleanedAdditionalFund = pdcResultForm.additional_fund.replace(/,/g, "")
        formData.append('additional_fund', cleanedAdditionalFund)
        formData.append('fundsource_id', pdcResultForm.fundsource_id)
        formData.append('remarks', pdcResultForm.remarks)

        // pdcFiles.value.forEach(file => {
        //     formData.append('files[]', file)
        //     formData.append('file_types[]', 'pdc')
        // })

        // sbResFiles.value.forEach(file => {
        //     formData.append('files[]', file)
        //     formData.append('file_types[]', 'sbRes')
        // })

        formData.append('site_problem_id', siteProblemId)

        try {
            const { data } = await api.post('/api/site_problems/pdc_result', formData, {
                headers: { 'Content-Type': 'multipart-form-data' }
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
        }
    }

    const submitFundedProject = async ({ mode = 'add', projectId = null, onSuccess, fundedProjectId = null } = {}) => {
        isSubmitting.value = true

        const formData = new FormData()
        formData.append('project_id', projectId)
        formData.append('title', fundedProjectForm.title)
        formData.append('has_supplemental_budget', hasSupplementalBudget.value ? 1 : 0)
        formData.append('fundsource_id', fundedProjectForm.fundsource_id)
        const cleanedApprovedCost = fundedProjectForm.approved_cost.replace(/,/g, "")
        formData.append('approved_cost', cleanedApprovedCost)
        formData.append('number', fundedProjectForm.number)
        formData.append('reference_number', fundedProjectForm.reference_number)
        formData.append('year_funded', fundedProjectForm.year_funded)
        formData.append('sb_number', fundedProjectForm.sb_number)
        formData.append('is_admin', isAdmin.value ? 1 : 0)
        formData.append('status', 'funded')
        formData.append('input_type', 'funded')
        fundedProjectForm.locations.forEach((location, index) => {
            formData.append(`locations[${index}][municipality_id]`, location.municipality_id)
            formData.append(`locations[${index}][barangay_id]`, location.barangay_id)
            formData.append(`locations[${index}][sitio]`, location.sitio)
        });
        resetErrorBag()
        try {
            let url = '/api/funded_project'
            let method = 'post'

            if (mode === 'edit' && fundedProjectId) {
                url = `/api/funded_project/${fundedProjectId}?_method=PUT`
            }

            const { data } = await api.post(url, formData, {
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

    const resetForm = () => {
        fundedProjectForm.selectedProject = ''
        fundedProjectForm.title = ''
        fundedProjectForm.fundsource_id = ''
        fundedProjectForm.number = ''
        fundedProjectForm.reference_number = ''
        fundedProjectForm.approved_cost = ''
        fundedProjectForm.has_supplemental_budget = 0
        fundedProjectForm.sb_number = ''
        fundedProjectForm.is_admin = 0
        fundedProjectForm.year_funded = ''
        fundedProjectForm.locations = [{ municipality_id: '', barangay_id: '', sitio: '' }]
    }

    watch(() => route.query.filter, (newVal) => {
        filterType.value = newVal || 'all'
        applyFilter()
    })

    return {
        loading,
        fundedProjects,
        error,
        fundedProject,
        isSubmitting,
        hasSupplementalBudget,
        isAdmin,
        pdcFiles,
        pdcFileInput,
        sbResFiles,
        sbResFileInput,
        fundedProjectForm,
        pdcResultForm,
        fetchFundedProjects,
        applyFilter,
        fetchFundedProject,
        submitFundedProject,
        submitPdcResult,
        resetForm
    }
}