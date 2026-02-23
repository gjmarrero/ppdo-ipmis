export function useValidations() {
    const { api } = useAxios()
    const { resetErrorBag, transformValidationErrors } = useCustomError()

    const isSubmitting = ref(false)
    const errors = ref({})
    const validationForm = reactive({
        project_id: '',
        title: '',
        cost: '',
        date_assigned: '',
        date_validated: '',
        date_validation_report: '',
        project_type_id: '',
        present_during_validation: '',
        actions_recommendations: '',
        remarks: '',
        responsible_persons: [
            { employee_id: '' }
        ],
        beneficiaries: {},
        number_of_females: '',
        number_of_males: '',
    })
    const files = ref([]);
    const fileInput = ref(null)
    const images = ref([])
    const mapImages = ref([])
    const imageInput = ref(null)
    const mapImageInput = ref(null)
    const beneficiaryInputs = ref({})
    const requirementTypes = [
        {
            id: 'locational_clearance',
            label: 'Locational Clearance',
        },
        {
            id: 'building_permit',
            label: 'Building Permit'
        },
        {
            id: 'excavation_permit',
            label: 'Excavation Permit'
        },
        {
            id: 'occupancy_permit',
            label: 'Occupancy Permit'
        },
        {
            id: 'river_right_of_way',
            label: 'River Right of Way'
        },
        {
            id: 'road_right_of_way',
            label: 'Road Right of Way'
        },
        {
            id: 'tree_cutting_permit',
            label: 'Tree cutting permit'
        },
        {
            id: 'pamb_clearance',
            label: 'PAMB Clearance'
        }
    ]
    const selectedRequirementTypes = ref([])
    const selectedPambType = ref('')

    const resetForm = () => {
        Object.assign(validationForm, {
            project_id: '',
            title: '',
            cost: '',
            date_assigned: '',
            date_validated: '',
            date_validation_report: '',
            project_type_id: '',
            present_during_validation: '',
            actions_recommendations: '',
            remarks: '',
            responsible_persons: [{ employee_id: '' }],
            beneficiaries: {},
            number_of_females: '',
            number_of_males: '',
        })
        files.value = []
        images.value = []
        mapImages.value = []
        selectedRequirementTypes.value = []
    }

    const submitValidation = async ({ mode = 'add', validationId = null, projectId, onSuccess, pamb = null } = {}) => {
        isSubmitting.value = true
        errors.value = {}
        try {
            const formDataObject = new FormData()
            const excludeKeys = ['responsible_persons', 'beneficiaries', 'project_id']

            if (projectId) {
                formDataObject.append('project_id', projectId)
            }
            Object.keys(validationForm).forEach(key => {
                if (!excludeKeys.includes(key)) {
                    formDataObject.append(key, validationForm[key] ?? '')
                }
            })

            validationForm.responsible_persons.forEach((id, index) => {
                formDataObject.append(`responsible_persons[${index}]`, id)
            })

            files.value.forEach(file => formDataObject.append('files[]', file))

            images.value.forEach((img, index) => {
                formDataObject.append(`images[${index}][file]`, img.file)
                formDataObject.append(`images[${index}][lat]`, img.lat)
                formDataObject.append(`images[${index}][long]`, img.long)
            })

            mapImages.value.forEach((img, index) => {
                formDataObject.append('mapImages[]', img.file)
            })

            validationForm.beneficiaries = { ...beneficiaryInputs.value }
            Object.entries(validationForm.beneficiaries).forEach(([id, value]) => {
                formDataObject.append(`beneficiaries[${id}]`, value)
            })

            selectedRequirementTypes.value.forEach((id) => {
                formDataObject.append("selectedRequirementTypes[]", id)
            })

            if (selectedRequirementTypes.value.includes(pamb) && selectedPambType.value) {
                formDataObject.append("pamb_type", selectedPambType.value)
            }


            let url = '/api/validation'
            let method = 'post'

            if (mode === 'edit' && validationId) {
                url = `/api/validation/${validationId}?_method=PUT`
            }

            const { data } = await api.post(url, formDataObject, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })

            resetForm()

            if (onSuccess) onSuccess(data)

            return data
        } catch (err) {
            if (err.response?.data?.errors) {
                errors.value = err.response.data.errors
            } else {
                console.error('Unexpected error', err)
            }
            throw err
        } finally {
            isSubmitting.value = false
        }
    }

    return {
        isSubmitting,
        errors,
        validationForm,
        files,
        fileInput,
        images,
        mapImages,
        imageInput,
        mapImageInput,
        beneficiaryInputs,
        requirementTypes,
        selectedRequirementTypes,
        selectedPambType,
        submitValidation,
        resetForm
    }
}