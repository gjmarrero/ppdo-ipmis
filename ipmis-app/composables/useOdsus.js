export function useOdsus() {
    const { api } = useAxios()
    const odsus = ref([])
    const odsusAsSource = ref([])
    const divisions = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)
    const odsuForm = reactive({
        'office_id': '',
        'division_id': ''
    })

    const resetForm = () => {
        odsuForm.office_id = ''
        odsuForm.division_id = ''
    }

    const fetchOdsus = async () => {
        const response = await api.get('/api/odsus')
        odsus.value = response.data.data
    }

    const fetchOdsusAsSource = async  () => {
        const response = await api.get('/api/fetchOdsus')
        odsusAsSource.value = response.data
    }

    const fetchDivisions = async () => {
        const response = await api.get('/api/fetchDivisions')
        divisions.value = response.data
    }

    const submitOdsuForm = async ({ odsuToEdit = null, onSuccess }) => {
        isSubmitting.value = true
        try {
            let data
            if (odsuToEdit) {
                ; data = await api.put(`/api/odsu/${odsuToEdit.id}`, odsuForm)
            } else {
                ; data = await api.post('/api/odsu', odsuForm)
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
        odsus,
        fetchOdsus,
        odsusAsSource,
        fetchOdsusAsSource,
        divisions,
        fetchDivisions,
        odsuForm,
        resetForm,
        submitOdsuForm,
        error,
        isSubmitting
    }
}