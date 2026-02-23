<template>
    <Dialog v-model:open="props.modelValue">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Activity</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="addPreEngineeringActivity">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow mb-8">
                    <FormGroup placeholder="Activity" v-model="activityForm.activity" :errorMessage="errorBag.activity"
                        name="activity" label="Activity/Status" type="text" />
                    <div>
                        <FormLabel>Employee In-Charge</FormLabel>
                        <FormCombobox :options="employees" placeholder="Select Employee"
                            v-model="activityForm.employee_id" />
                    </div>
                    <div>
                        <FormLabel>Remarks</FormLabel>
                        <Textarea placeholder="Remarks" v-model="activityForm.remarks" />
                    </div>
                </div>
                <div class="mt-10">
                    <Button type="submit" variant="primary">Submit</Button>
                </div>
            </form>
        </DialogContent>
        <DialogFooter>

        </DialogFooter>
    </Dialog>
</template>

<script setup>
import { Bold } from 'lucide-vue-next'
import Button from '../Button.vue'


const { api } = useAxios()

const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const props = defineProps({
    pre_engineering: Object,
    modelValue: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['submitted','update:modelValue'])

const activityForm = reactive({
    preengineering_status_id: '',
    activity: '',
    employee_id: '',
    remarks: '',
})

const addPreEngineeringActivity = async () => {
    activityForm.preengineering_status_id = props.pre_engineering[0].id
    try{
        let response = await api.post('/api/preengineering_activity', activityForm)
        emit('submitted', response.data.data)
    } catch (error) {
        console.log('Save failed:', error)
    } finally {
        console.log('Save successfully')
    }
    
}

const employees = ref([])

const fetchEmployees = () => {
    api.get('/api/fetchEmployees').then(({ data }) => {
        employees.value = data
    })
}

onMounted(() => {
    console.log("Mounted", props.pre_engineering)
    fetchEmployees()
})

</script>

<style lang="scss" scoped></style>