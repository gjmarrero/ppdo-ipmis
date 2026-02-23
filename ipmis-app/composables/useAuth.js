export default function useAuth() {

    const user = useState('auth-user', () => null)

    const employees = ref([])

    const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

    const { api, csrf } = useAxios()

    async function me() {
        try {
            await csrf() // make sure CSRF cookie exists
            const { data } = await api.get('/api/me')
            user.value = data
            return data
        } catch (err) {
            if (err.response && err.response.status === 401) {
                // Guest user → just reset state
                user.value = null
                return null
            }
            // Log other errors
            console.error("me() failed:", err)
            user.value = null
            return null
        }
    }

    function login(userForm) {
        resetErrorBag()
        csrf().then(() => {
            api.post('/login', userForm).then(({ data }) => {
                user.value = data.user
                $fetch('/api/set-cookie', {
                    method: 'POST',
                    body: { token: data.token }
                }).then(res => {
                    navigateTo("/dashboard")
                })
            }).catch(err => {
                transformValidationErrors(err.response)
            })
        })
    }

    function logout() {
        api.post('/api/logout').then(() => {
            user.value = null
            $fetch('/api/logout', {
                method: 'POST',
            }).then(res => {
                navigateTo("/")
            })
        })
    }

    function register(userForm) {
        resetErrorBag()
        csrf().then(() => {
            api.post('/register', userForm).then(({ data }) => {
            }).catch(err => {
                transformValidationErrors(err.response)
            })
        })
    }

    return { login, logout, register, errorBag, user, me }
}