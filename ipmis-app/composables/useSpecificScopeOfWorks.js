export function useSpecificScopeOfWorks() {
    const { api } = useAxios()
    const scopes_of_work = ref([])
    const specificWorksAsSource = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)

    const scopeOfWorkForm = reactive({
        'scope': '',
    })

    const resetForm = () => {
        scopeOfWorkForm.scope = ''
    }

    const fetchSpecificWorksAsSource = async () => {
        error.value = null
        try {
            const response = await api.get('/api/fetchSpecificWorks')
            specificWorksAsSource.value = response.data
        } catch (err) {
            error.value = err
        }
    }   

    const fetchScopesOfWork = async () => {
        const response = await api.get('/api/scopes_of_work')
        scopes_of_work.value = response.data.data
    }

    const submitScopeOfWorkForm = async ({ scopeOfWorkToEdit = null, onSuccess }) => {
        isSubmitting.value = true
        try {
            let data
            if (scopeOfWorkToEdit) {
                ; data = await api.put(`/api/scope_of_work/${scopeOfWorkToEdit.id}`, scopeOfWorkForm)
            } else {
                ; data = await api.post('/api/scope_of_work', scopeOfWorkForm)
            }
            resetForm()
            if (onSuccess) onSuccess(data)

            return data
        } catch (error) {
            console.error('Save failed:', error)
        } finally {
            isSubmitting.value = false
        }
    }

    return {
        specificWorksAsSource,
        fetchSpecificWorksAsSource,
        scopeOfWorkForm,
        resetForm,
        isSubmitting,
        submitScopeOfWorkForm,
        scopes_of_work,
        fetchScopesOfWork,
    }
}