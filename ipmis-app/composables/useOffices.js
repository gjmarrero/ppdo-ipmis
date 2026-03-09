export function useOffices() {
    const { api } = useAxios()
    const offices = ref([])
    const officesAsSource = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)

    const officeForm = reactive({
        'office': '',
        'office_acc': ''
    })

    const resetForm = () => {
        officeForm.office = ''
        officeForm.office_acc = ''
    }

    const fetchOffices = async () => {
        error.value = null
        try {
            const response = await api.get('/api/offices')
            offices.value = response.data.data
        } catch (err) {
            error.value = err
        }        
    }

    const fetchOfficesAsSource = async () => {
        error.value = null
        try {
            const response = await api.get('/api/fetchOffices')
            officesAsSource.value = response.data
        } catch (err) {
            error.value = err
        }
    }

    const submitOfficeForm = async ({ officeToEdit = null, onSuccess }) => {
        isSubmitting.value = true
        try {
            let data
            if (officeToEdit) {
                ; data = await api.put(`/api/office/${officeToEdit.id}`, officeForm)
            } else {
                ; data = await api.post('/api/office', officeForm)
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
        offices,
        fetchOffices,
        officesAsSource,
        fetchOfficesAsSource,
        officeForm,
        resetForm,
        submitOfficeForm,
        error,
        isSubmitting
    }
}