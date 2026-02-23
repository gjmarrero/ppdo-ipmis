export function useEmployees() {
    const { api } = useAxios()
    const employees = ref([])
    const allEmployees = ref([])
    const error = ref(null)
    const loading = ref(false)

    // const fetchEmployees = async() => {
    //     error.value = null
    //     try {
    //         const response = await api.get('/api/fetchEmployees')
    //         employees.value = response.data
    //     }catch (err){
    //         error.value = err
    //     }
    // }

    const fetchEmployees = async (options = {}) => {
        try {
            loading.value = true
            error.value = null

            // Get current route path segment if not specified
            const route = useRoute()
            const pathSegment = options.path || route.path.split('/').pop()

            // Prepare query params correctly for Axios
            const params = {}
            if (options.office_id) params.office_id = options.office_id
            else if (pathSegment) params.path = pathSegment

            // ✅ Axios uses { params }, not { query }
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

    return {
        employees,
        allEmployees,
        fetchEmployees,
        fetchAllEmployees,
        error
    }

}