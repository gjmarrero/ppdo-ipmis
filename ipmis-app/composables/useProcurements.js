export function useProcurements() {
    const { api } = useAxios()
    const procurements = ref(null)

    const fetchProcurementDetails = async (project_title) => {
        console.log("Sent proj title", project_title)
        const response = await api.get(`/api/project_plan/${project_title}`, {
            headers: {
                Accept: 'application/json'
            }
        })
        procurements.value = response.data
    }

    return {
        procurements,
        fetchProcurementDetails
    }
}