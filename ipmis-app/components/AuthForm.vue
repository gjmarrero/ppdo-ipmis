<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <Card class="w-[450px] bg-cardbg text-textsecondary">
            <CardHeader>
                <div class="flex flex-col items-center text-center gap-2 sm:mx-auto sm:w-full sm:max-w-sm">
                    <img src="/logo.png" class="h-20 w-20" alt="Logo" />
                    <span class="font-bold text-3xl text-textsecondary font-talina">Benguet Infrastructure Project Monitoring System</span>
                    <Heading as="h5" class="mt-4 mb-0 text-xl font-semibold text-textsecondary">
                        <span v-if="props.formType == 'signin'">
                            Sign in to your account
                        </span>
                        <span v-else>
                            Create a new account
                        </span>
                    </Heading>
                </div>
            </CardHeader>
            <CardContent>
                <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form class="space-y-6" @submit.prevent="handleForm">

                        <FormGroup v-model="userForm.name" :errorMessage="errorBag.name"
                            v-if="props.formType == 'signup'" label="Full name" name="name" type="text"
                            autocomplete="name" required labelFor="name" />

                        <FormGroup v-model="userForm.email" :errorMessage="errorBag.email" label="Email Address"
                            name="email" type="email" autocomplete="email" required labelFor="email" />

                        <div class="my-2">
                            <FormGroup v-model="userForm.password" :errorMessage="errorBag.password" label="Password"
                                name="password" type="password" autocomplete="current-password" required
                                labelFor="password" />

                            <FormGroup v-model="userForm.password_confirmation"
                                :errorMessage="errorBag.password_confirmation" v-if="props.formType == 'signup'"
                                label="Password Confirmation" name="password_confirmation" type="password"
                                autocomplete="current-password" required labelFor="password" class="my-5" />
                            <div class="text-sm text-end" v-else>
                                <a href="#" class="font-semibold text-jetblack hover:text-jetblack">Forgot
                                    password?</a>
                            </div>
                        </div>

                        <div class="flex flex-col items-center text-center gap-2 sm:mx-auto sm:w-full sm:max-w-sm text-textprimary">
                            <Button type="submit" variant="newprimary">
                                <span v-if="props.formType == 'signin'">Sign In</span>
                                <span v-else>Sign Up</span>
                            </Button>
                        </div>
                    </form>


                </div>
            </CardContent>
            <CardFooter>
                <div class="flex flex-col items-center text-center gap-2 sm:mx-auto sm:w-full sm:max-w-sm">
                    <p class="mt-10 text-center text-sm text-textsecondary">
                        <span v-if="props.formType != 'signup'">
                            Not registered?
                            {{ ' ' }}
                            <NuxtLink to="/signup"
                                class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                                Register</NuxtLink>
                        </span>

                        <span v-else>
                            Already a member
                            {{ ' ' }}
                            <NuxtLink to="/" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login
                            </NuxtLink>
                        </span>
                    </p>
                </div>

            </CardFooter>
        </Card>
    </div>


</template>

<script setup>

const props = defineProps({
    formType: {
        type: String,
        default: 'signin',
        validator: prop => ['signin', 'signup'].includes(prop)
    }
})

const userForm = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const { login, register, errorBag } = useAuth()

function handleForm() {
    if (props.formType == 'signin') {
        login(userForm)
    } else {
        register(userForm)
    }
}
</script>