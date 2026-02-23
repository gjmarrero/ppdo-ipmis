<template>
    <div>

        <form @submit.prevent="submitForm">
            <div v-for="(location, index) in locations" :key="index" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 flex-grow mb-8">
                <div>
                    <FormLabel>Municipality</FormLabel>
                    <FormCombobox :options="municipalities" placeholder="Select municipality"
                        v-model="selectedMunicipality" />
                </div>

                <div>
                    <FormLabel>Barangay</FormLabel>
                    <FormCombobox :options="barangays" placeholder="Select barangay" v-model="location.barangay_id"
                        :disabled="!selectedMunicipality"/>
                </div>

                <FormGroup placeholder="Sitio" v-model="location.sitio" name="sitio" label="Sitio" />

                <button type="button" @click="removeLoc(index)" class="text-red-600 hover:underline">
                        Remove
                </button>
            </div>

            </div>
            <button type="button" @click="addLoc" class="bg-blue-500 text-white px-4 py-2 rounded">
                Add Item
            </button>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded ml-2">
                Submit
            </button>
        </form>
    </div>
</template>

<script setup>

const {api} = useAxios()

const locations = ref([
    {municipality_id: '', barangay_id: '', sitio: ''}
])

const addLoc = () => {
    locations.value.push({ municipality_id: '', barangay_id: '', sitio: ''})
}

const removeLoc = () => {
    locations.value.splice(index, 1)
}

const submitForm = async () => {
    try {
        const response = await axios.post('/api/submit-form', { items: items.value });
        alert(response.data.message);
    } catch (error) {
        console.error(error);
        alert('An error occurred while submitting the form.');
    }
};
</script>

<style scoped>
/* Add any necessary styles */
</style>