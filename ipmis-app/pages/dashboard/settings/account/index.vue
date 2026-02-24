<template>
    <MetaTags title="Account" />
    <div>
        <SettingsAccount @form-submitted="handleFormSubmission"/>
        <Toaster />
    </div>

</template>

<script setup>
import { Toaster, useToast } from '@/components/ui/toast'

const { toast } = useToast()

const handleFormSubmission = () => {
    toast({
        title: 'Account',
        description: 'Updated successfully'
    })
}

definePageMeta({
    middleware: ['auth']
})
</script>