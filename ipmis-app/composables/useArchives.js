export function useArchives() {
    const { api } = useAxios()
    const archives = ref([])
    const error = ref(null)
    const supplementalBudgets = ref([])

    const fetchArchives = async () => {
        error.value = null
        try {
            const response = await api.get('/api/archives')
            archives.value = response.data.data
            console.log("Archives", archives.value)
        } catch (err) {
            error.value = err
        }
    }

    const archiveForm = reactive({
        doc_type: '',
        number: '',
        description: '',
    })

    const files = ref([])

    const fileInput = ref(null)

    const submitArchiveForm = async ({ onSuccess = {} }) => {
        const formData = new FormData()

        formData.append('doc_type', archiveForm.doc_type)
        formData.append('number', archiveForm.number)
        formData.append('description', archiveForm.description)
        if (files.value.length > 0) {
            formData.append('file', files.value[0])
        }

        try {
            const { data } = await api.post('/api/archive', formData, {
                headers: { 'Content-Type': 'multipart-form-data' }
            })
            console.log("Data from submitted", data)
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

    const fetchArchivedSupplementalBudgets = async() => {
        const response = await api.get('/api/fetchArchivedSupplementalBudgets')
        supplementalBudgets.value = response.data
    }

    return {
        archives,
        fetchArchives,
        error,
        archiveForm,
        files,
        fileInput,
        submitArchiveForm,
        supplementalBudgets,
        fetchArchivedSupplementalBudgets,
    }
}