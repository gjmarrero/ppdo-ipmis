<template>
    <div class="flex flex-col gap-2 p-4 rounded-lg shadow relative border border-borderblackui bg-cardbg">
        <!-- <TrashIcon class="h-5 w-5 text-red-600" @click="deleteFundsource" /> -->
        <NuxtLink :to="`/dashboard/settings/fundsources/${fundsource.id}`" class="flex flex-col gap-1 p-4 rounded-lg shadow">
            <Heading as="h4" class="text-start">
                <span>{{ fundsource?.fundsource_acc }}</span>
            </Heading>
            <Heading as="h6" class="text-start">{{ fundsource?.fundsource }}</Heading>
        </NuxtLink>
    </div>
</template>

<script setup>
import { TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    fundsource: Object
})

const emit = defineEmits(['delete'])

const { api } = useAxios()

function deleteFundsource() {
    api.delete(`/api/fundsource/${props.fundsource.id}`).then(() => {
        console.log("deleted")
        emit('delete', props.fundsource.id)
    })
}
</script>