export function usePlantillas() {
    const { api } = useAxios()
    const plantillas = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)

    const plantillaForm = reactive({
        'odsu_id': '',
        'position_id': '',
        'employee_id': ''
    })

    const resetForm = () => {
        plantillaForm.odsu_id = ''
        plantillaForm.position_id = ''
        plantillaForm.employee_id = ''
    }

    const fetchPlantillas = async () => {
        const response = await api.get('/api/plantillas')
        plantillas.value = response.data.data
    }

    const submitPlantillaForm = async ({ plantillaToEdit = null, onSuccess }) => {
        isSubmitting.value = true
        try {
            let data
            if (plantillaToEdit) {
                ; data = await api.put(`/api/plantilla/${plantillaToEdit.id}`, plantillaForm)
            } else {
                ; data = await api.post('/api/plantilla', plantillaForm)
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
        plantillas,
        fetchPlantillas,
        plantillaForm,
        resetForm,
        submitPlantillaForm,
        error,
        isSubmitting
    }
}