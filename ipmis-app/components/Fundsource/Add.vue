<template>
    <div>
        <div class="flex flex-row justify-end">
            <Button @click="toggleDrawer" variant="newprimary" class="px-4 py-2 w-40">
                Add Fund Source
            </Button>
        </div>

        <RightDrawer v-model:isOpen="isDrawerOpen" class="w-[600px]">
            <template #header>
                <div class="custom-header">
                    <h2 class="font-mono text-lg font-semibold">Add fund source</h2>
                </div>
            </template>
            <form class="grid grid-cols-1 md:grid-cols-1 gap-2 flex-grow" @submit.prevent="addFundsource">
                <FormGroup name="fundsource" v-model="fundsource.fundsource" :errorMessage="errorBag.fundsource"
                    label="Fund Source" />
                <FormGroup name="fundsource_code" v-model="fundsource.fundsource_code"
                    :errorMessage="errorBag.fundsource_code" label="Fund Source Code" />
                <FormGroup name="fundsource_abbrev" v-model="fundsource.fundsource_abbrev"
                    :errorMessage="errorBag.fundsource_abbrev" label="Fund Source Abbreviation" />
                <div class="flex flex-row justify-end py-4">
                    <Button type="submit" class="py-2 w-40" variant="newprimary">Save</button>
                </div>
            </form>
        </RightDrawer>
    </div>
</template>

<script setup>

const isDrawerOpen = ref(false);

const toggleDrawer = () => {
    isDrawerOpen.value = !isDrawerOpen.value;
};

const fundsource = reactive({
    fundsource: "",
    fundsource_code: "",
    fundsource_abbrev: ""
})

const { api } = useAxios()
const { errorBag, transformValidationErrors, resetErrorBag } = useCustomError()

const emit = defineEmits(['add'])

const resetForm = () => {
    fundsource.fundsource = ""
    fundsource.fundsource_code = ""
    fundsource.fundsource_abbrev = ""
}

function addFundsource() {
    resetErrorBag()
    api.post("/api/fundsource", fundsource).then(({ data }) => {
        isDrawerOpen.value = false
        resetForm()
        emit('add', data)
    }).catch((error) => {
        transformValidationErrors(error.response)
    })
}
</script>