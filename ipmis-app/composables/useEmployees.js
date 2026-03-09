export function useEmployees() {
    const { api } = useAxios()
    const employees = ref([])
    const allEmployees = ref([])
    const error = ref(null)
    const loading = ref(false)
    const isSubmitting = ref(false)

    const employeeForm = reactive({
        'last_name': '',
        'first_name': '',
        'middle_name': '',
    })

    const resetForm = () => {
        employeeForm.last_name = ''
        employeeForm.first_name = ''
        employeeForm.middle_name = ''
    }

    const fetchEmployees = async (options = {}) => {
        try {
            loading.value = true
            error.value = null

            const route = useRoute()
            const pathSegment = options.path || route.path.split('/').pop()

            const params = {}
            if (options.office_id) params.office_id = options.office_id
            else if (pathSegment) params.path = pathSegment
            const response = await api.get('/api/fetchEmployees', { params })

            employees.value = response.data || []
        } catch (err) {
            console.error('Error fetching employees:', err)
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const fetchAllEmployees = async () => {
        error.value = null
        try {
            const response = await api.get('/api/employees')
            allEmployees.value = response.data.data
        } catch (err) {
            error.value = err
        }
    }

    const submitEmployeeForm = async ({ employeeToEdit = null, onSuccess }) => {
        isSubmitting.value = true
        try {
            let data
            if (employeeToEdit) {
                ; data = await api.put(`/api/employee/${employeeToEdit.id}`, employeeForm)
            } else {
                ; data = await api.post('/api/employee', employeeForm)
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
        employees,
        allEmployees,
        fetchEmployees,
        fetchAllEmployees,
        error,
        employeeForm,
        resetForm,
        isSubmitting,
        submitEmployeeForm,
    }

}