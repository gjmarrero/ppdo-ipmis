<template>
    <MetaTags title="Sign In" description="Sign in to your account" />
    <div class="auth-page">
        <AuthForm />
    </div>

</template>

<script setup>
definePageMeta({
    middleware: ['guest']
})

</script>

<style scoped>
.auth-page {
    min-height: 100vh;
    background-image: url('/images/bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    display: flex;
    justify-content: center;
    align-items: center;
}
</style>