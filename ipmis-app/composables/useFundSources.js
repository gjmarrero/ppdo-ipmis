export function useFundSources() {
    const { api } = useAxios()
    const fundSources = ref([])
    const error = ref(null)

    const fetchFundSources = async() => {
        error.value = null
        try{
            const response = await api.get('/api/fetchFundsources')
            fundSources.value = response.data
        }catch(err){
            error.value = err
        }
    }

    return{
        fundSources,
        fetchFundSources,
        error
    }
}