<template>
    <MetaTags title="Sign Up" description="Sign up to your account" />
    <div>
        <AuthForm formType="signup" />
    </div>

</template>

<script setup>
definePageMeta({
    middleware: ['guest']
})
</script>