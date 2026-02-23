export default defineNuxtPlugin(async () => {
    const token = useCookie('XSRF-TOKEN')
    if (token.value) {
        const { me } = useAuth()
        await me()
    }
})
