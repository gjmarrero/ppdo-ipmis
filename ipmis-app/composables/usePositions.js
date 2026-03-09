export function usePositions() {
    const { api } = useAxios()
    const positions = ref([])
    const positionsAsSource = ref([])
    const filteredPositions = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)

    const positionForm = reactive({
        'odsu_id': '',
        'title': ''
    })

    const resetForm = () => {
        positionForm.odsu_id = ''
        positionForm.title = ''
    }

    const fetchPositions = async () => {
        const response = await api.get('/api/positions')
        positions.value = response.data.data
    }
    
    const fetchPositionsAsSource = async () => {
        const response = await api.get('/api/fetchPositions')
        positionsAsSource.value = response.data
    }

    const submitPositionForm = async ({ positionToEdit = null, onSuccess }) => {
        isSubmitting.value = true
        try {
            let data
            if (positionToEdit) {
                ; data = await api.put(`/api/position/${positionToEdit.id}`, positionForm)
            } else {
                ; data = await api.post('/api/position', positionForm)
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
        positions,
        fetchPositions,
        positionsAsSource,
        fetchPositionsAsSource,
        filteredPositions,
        positionForm,
        submitPositionForm,
        resetForm,
        isSubmitting,
    }
}