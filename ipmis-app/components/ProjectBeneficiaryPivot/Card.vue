<template>
    <div class="flex flex-col border border-borderblackui rounded-lg shadow p-2 bg-cardbg">
        <div class="grid grid-cols-1 text-textsecondary">
            <Heading as="h5" class="text-textprimary">
                <span>{{ projecttype?.name }}</span>
            </Heading>
            <span v-for="beneficiary_type in beneficiary_types">
                <p class="text-sm text-center">
                    {{ beneficiary_type.beneficiary_type }}
                </p>
            </span>
        </div>
        <!-- <div class="flex items-center">
            <FormComboBoxDef :options="beneficiary_types" placeholder="Select  type"
            v-model="beneficiaryTypeForm.beneficiary_type_id" @update:modelValue="handleBeneficiaryTypeSelect" />
            <button type="submit"><Plus /></button>
        </div> -->
    </div>
    <!-- <div class="flex gap-2 p-4 rounded-lg shadow relative justify-between w-full">

        <PlusIcon class="h-5 w-5 text-blue-600" @click="addBeneficiaryType" />
        <div class="flex justify-between items-center w-full">
            <Heading as="h5" class="text-start">
                <span>{{ projecttype?.name }}</span>
                <TrashIcon class="h-5 w-5 text-red-600" @click="deleteBeneficiaryType" />
            </Heading>
        </div>
        <NuxtLink :to="`/dashboard/settings/beneficiaries/${projecttype.id}`"
            class="flex flex-col gap-1 p-4 rounded-lg shadow">
            

            <Heading as="h6" class="text-start">{{ projecttype?.name }}</Heading>
        </NuxtLink>
    </div> -->
</template>

<script setup>
import { TrashIcon } from '@heroicons/vue/24/outline';
import { PlusIcon } from '@heroicons/vue/20/solid';
import { Plus } from 'lucide-vue-next';
import Button from '../Button.vue';

const { api } = useAxios()

const props = defineProps({
    projecttype: Object
})

const beneficiaryTypeForm = reactive({
    beneficiary_type_id: null,
})

const beneficiary_types = ref([])

const fetchBeneficiaryTypes = () => {
    api.get(`/api/fetchBeneficiaryTypes/${props.projecttype.id}`).then(({ data }) => {
        beneficiary_types.value = data.data
        console.log("BeneType:", beneficiary_types.value)
    })
}

// const handleBeneficiaryTypeSelect = () => {
//     api.post("/api/project_beneficiary", beneficiaryTypeForm).then(({ data }) => {
//         console.log('details', beneficiaryTypeForm)
//         // emit('add', data)
//     })
// }

onMounted(() => {
    fetchBeneficiaryTypes()
})

// const emit = defineEmits(['delete'])

// function deleteFundsource() {
//     api.delete(`/api/fundsource/${props.fundsource.id}`).then(() => {
//         console.log("deleted")
//         emit('delete', props.fundsource.id)
//     })
// }
</script>