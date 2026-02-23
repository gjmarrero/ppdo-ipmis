export function useSpecificScopeOfWorks() {
    const { api } = useAxios()
    const specificWorks = ref([])
    const error = ref(null)

    const fetchSpecificWorks = async () => {
        error.value = null
        try {
            const response = await api.get('/api/fetchSpecificWorks')
            specificWorks.value = response.data
        } catch (err) {
            error.value = err
        }
    }

    return {
        specificWorks,
        fetchSpecificWorks,
    }
}