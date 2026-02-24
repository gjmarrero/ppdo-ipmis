<template>
    <div class="flex w-full max-w-3xl flex-col gap-6 mx-auto mt-20">
        <Tabs default-value="account">
            <TabsList class="bg-cardbg">
                <TabsTrigger value="account">
                    Account
                </TabsTrigger>
                <TabsTrigger value="password">
                    Password
                </TabsTrigger>
            </TabsList>
            <form @submit.prevent="handleSubmit">
                <TabsContent value="account">
                    <Card class="bg-cardbg border border-cardborder text-textsecondary">
                        <CardHeader>
                            <CardTitle>Account</CardTitle>
                            <CardDescription>
                                Make changes to your account here. Click save when you're
                                done.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="grid gap-6">
                            <FormGroup v-model="userForm.name" :errorMessage="errorBag.name" label="Full name"
                                name="name" type="text" autocomplete="name" required labelFor="name"
                                readonly="readonly" />
                            <FormGroup v-model="userForm.email" :errorMessage="errorBag.email" label="Email"
                                name="email" type="email" autocomplete="email" required labelFor="email"
                                :disabled="!isEditing" />
                        </CardContent>
                        <CardFooter class="flex flex-row justify-end text-textprimary">
                            <Button v-if="!isEditing" @click="isEditing = true" variant="newprimary">Edit</Button>
                            <!-- <Button v-if="isEditing" @click="handleForm" variant="newprimary">Save changes</Button> -->

                            <Button v-else type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                                <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                                <span v-else>Submit</span>
                            </Button>
                        </CardFooter>
                    </Card>
                </TabsContent>
            </form>
            <form @submit.prevent="handlePasswordSubmit">
                <TabsContent value="password">
                    <Card class="bg-cardbg border border-cardborder text-textsecondary">
                        <CardHeader>
                            <CardTitle>Password</CardTitle>
                            <CardDescription>
                                Change your password here.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="grid gap-6">
                            <p v-if="errorMessage" class="mt-2 text-sm text-red-600 text-center">
                                {{ errorMessage }}
                            </p>
                            <FormGroup v-model="userForm.current_password" :errorMessage="errorBag.current_password"
                                label="Current Password" name="current_password" type="password" required
                                labelFor="current_password" />
                            <FormGroup v-model="userForm.password" :errorMessage="errorBag.password"
                                label="New Password" name="password" type="password" required labelFor="password" />
                        </CardContent>
                        <CardFooter class="flex flex-row justify-end text-textprimary">
                            <Button type="submit" variant="newprimary" size="lg" :disabled="isSubmitting">
                                <Spinner v-if="isSubmitting" :show="true" size="lg" label="Saving..." />
                                <span v-else>Submit</span>
                            </Button>
                        </CardFooter>
                    </Card>
                </TabsContent>
            </form>

        </Tabs>
    </div>
</template>

<script setup>

const { user, logout } = useAuth()
const { errorBag, resetErrorBag, transformValidationErrors } = useCustomError()
const { api } = useAxios()

const emit = defineEmits(['form-submitted'])

const userForm = reactive({
    current_password: '',
    ...user.value
})

const isEditing = ref(false)

const isSubmitting = ref(false)
const errorMessage = ref('')

watch(user, (newUser) => {
    if (newUser) {
        console.log("User", newUser)
        userForm.name = newUser.name
        userForm.email = newUser.email
    }
})

async function handleSubmit() {
    isSubmitting.value = true
    errorMessage.value = ''

    try {
        const response = await api.put('/api/user/account', userForm)
        Object.assign(user.value, response.data)
        emit('form-submitted')
    } catch (error) {
        resetErrorBag()

        if (error.response?.data?.errors) {
            transformValidationErrors(error.response)
        } else if (error.response?.data?.message) {
            errorMessage.value = error.response.data.message
        } else {
            errorMessage.value = error.response?.data?.message || 'Update failed'
        }
    } finally {
        isSubmitting.value = false
        isEditing.value = false
    }
}

async function handlePasswordSubmit() {
    isSubmitting.value = true
    errorMessage.value = ''
    resetErrorBag()

    try {
        const response = await api.put('/api/user/password', userForm, { withCredentials: true})
        Object.assign(user.value, response.data)
        emit('form-submitted')
        await logout()
    } catch (error) {
        console.error(error)
        errorMessage.value = error.response?.data?.message || 'Update failed'
    } finally {
        isSubmitting.value = false
    }
}

</script>