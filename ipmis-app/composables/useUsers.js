export function useUsers() {
    const { api } = useAxios()
    const { transformValidationErrors, resetErrorBag } = useCustomError()
    const users = ref([])
    const error = ref(null)
    const isSubmitting = ref(false)

    const roleOptions = [
        { id: 'ppdoDevChief', name: 'PPDO Project Dev Chief' },
        { id: 'ppdoDevStaff', name: 'PPDO Project Dev Staff' },
        { id: 'ppdoMonitoringChief', name: 'PPDO Project Monitoring Chief' },
        { id: 'ppdoMonitoringStaff', name: 'PPDO Project Monitoring Staff' },
        { id: 'peoPlanningChief', name: 'PEO Planning Chief' },
        { id: 'peoPlanningStaff', name: 'PEO Planning Staff' },
        { id: 'benroChief', name: 'BENRO Division Chief' },
        { id: 'benroStaff', name: 'BENRO Staff' },
        { id: 'peoConstructionChief', name: 'PEO Construction Chief' },
        { id: 'peoConstructionStaff', name: 'PEO Construction Staff' },
    ]

    const userForm = reactive({
        'employee_id': '',
        'name': '',
        'email': '',
        'password': '',
        'password_confirmation': '',
        'role': ''
    })

    const resetForm = () => {
        userForm.employee_id = ''
        userForm.name = ''
        userForm.email = ''
        userForm.password = ''
        userForm.password_confirmation = ''
        userForm.role = ''
    }

    const fetchUsers = async () => {
        const response = await api.get('/api/users')
        users.value = response.data.data
    }

    const submitUserForm = async ({ userToEdit = null, onSuccess }) => {
        resetErrorBag()
        isSubmitting.value = true
        try {
            let data
            if (userToEdit) {
                ; data = await api.put(`/api/user/${userToEdit.id}`, userForm)
            } else {
                ; data = await api.post('/api/user', userForm)
            }
            resetForm()
            if (onSuccess) onSuccess(data)

            return data
        } catch (error) {
            if (error.response && error.response.status === 422) {
                transformValidationErrors(error.response)
            } else {
                console.error('Save failed:', error)
            }
        } finally {
            isSubmitting.value = false
        }
    }

    return {
        roleOptions,
        users,
        fetchUsers,
        userForm,
        resetForm,
        submitUserForm,
        error,
        isSubmitting
    }
}