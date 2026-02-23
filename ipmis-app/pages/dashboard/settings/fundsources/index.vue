<template>
    <MetaTags title="Fundsources" />
    <div class="flex flex-col gap-10 px-4 py-4">
        <ClientOnly><Toaster /></ClientOnly>
        <FundsourceAdd @add="handleFundsourceAddition"/>        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 shadow">
            <FundsourceCard @delete="handleFundsourceDeletion" v-for="fundsource in fundsources" :key="fundsource.id" :fundsource="fundsource" />
        </div>
    </div>
</template>

<script setup>
import { useToast } from '@/components/ui/toast'
import Toaster from '@/components/ui/toast/Toaster.vue'

const {api} = useAxios()

const {toast} = useToast()

const fundsources = ref([])

onMounted(() => {
    loadFundsources()
})

function loadFundsources(){
    api.get('/api/fundsources').then(({data}) => {
        fundsources.value = data.data
    })
}

function handleFundsourceAddition(fundsource){
    fundsources.value = [fundsource.data, ...fundsources.value]
    toast({
        title: 'Success',
        description: 'Fund source added'
    })
}

function handleFundsourceDeletion(fundsourceId){
    fundsources.value = fundsources.value.filter(fundsource => fundsource.id !== fundsourceId)
}
</script>