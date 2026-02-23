export function useProjectTypes() {
    const { api } = useAxios()
    const projectTypes = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)

    const projectTypeForm = reactive({
        project_type: '',
        project_type_code: '',
    })

    const resetForm = () => {
        projectTypeForm.project_type = ''
        projectTypeForm.project_type_code = ''
    }

    const fetchProjectTypes = async () => {
        error.value = null
        try {
            const response = await api.get('/api/fetchProjectTypes')
            projectTypes.value = response.data
        } catch (err) {
            error.value = err
        }
    }    

    const submitProjectTypeForm = async ({projectTypeToEdit=null, onSuccess}) => {
        isSubmitting.value = true
        try {
            let data
            if (projectTypeToEdit) {
                ;data = await api.put(`/api/project_type/${projectTypeToEdit.id}`, projectTypeForm)
            } else {
                ;data = await api.post('/api/project_type', projectTypeForm)
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
        projectTypes,
        fetchProjectTypes,
        projectTypeForm,
        submitProjectTypeForm,
        error
    }
}