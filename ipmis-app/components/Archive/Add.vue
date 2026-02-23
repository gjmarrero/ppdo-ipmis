<template>
    <RightDrawer v-model:isOpen="isDrawerOpen" @update:isOpen="emit('update:isArchiveDrawerOpen', $event)">
        <div>
            <form @submit.prevent="handleArchiveFormSubmit">
                <div class="mb-4">
                    <FormLabel>Document type</FormLabel>
                    <FormCombobox :options="docTypeOptions" v-model="archiveForm.doc_type"
                        :errorMessage="errorBag.doc_type" />
                </div>

                <FormGroup placeholder="Description/Title" v-model="archiveForm.description"
                    :errorMessage="errorBag.description" name="description" type="text" label="Description/Title"
                    class="mb-4" />

                <FormGroup placeholder="Number" v-model="archiveForm.number" :errorMessage="errorBag.number"
                    name="number" type="text" label="Document number" class="mb-4" />

                <div class="grid grid-cols-1 md:grid-cols-2 flex-grow my-8">
                    <div>
                        <div>
                            <FormLabel>File</FormLabel>
                            <input type="file" id="fileUpload" class="hidden" ref="fileInput"
                                @change="handleFileChange" />
                            <Button variant="secondary" type="button" @click="triggerFileInput"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Choose Files
                            </Button>
                        </div>
                        <div v-if="files.length" class="flex flex-wrap mt-4">
                            <span v-for="(file, index) in files" :key="index"
                                class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm mr-2">
                                {{ file.name }}
                                <button @click.prevent="removeFile(index)" class="ml-1 text-xs text-white">
                                    &times;
                                </button>
                            </span>
                        </div>
                    </div>

                    <!-- <div class="px-2">
                            <span v-if="project?.validation?.files.length > 0">
                                <p class="text-sm text-muted-foreground">Existing Files</p>
                                <span v-for="file in project?.validation?.files">
                                    <p class="text-sm font-medium">
                                        <a :href="file.file_path" target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 underline">
                                            {{ file.file_path.split('/').pop() }}
                                        </a>
                                    </p>
                                </span>
                            </span>
                        </div> -->

                </div>
                <Button type="submit" variant="primary" :disabled="loading">Submit</Button>
            </form>
        </div>
    </RightDrawer>
</template>

<script setup>
import Button from '../Button.vue'

const { api } = useAxios()

const { user } = useAuth()

const { errorBag } = useCustomError()

const { archiveForm, files, fileInput, submitArchiveForm } = useArchives()

const props = defineProps({
    isArchiveDrawerOpen: {
        type: Boolean,
        default: false,
    },
    // archiveToEdit: {
    //     type: Object,
    //     default: null,
    // }
})

const emit = defineEmits(['update:isArchiveDrawerOpen', 'archiveAdded'])

const isDrawerOpen = ref(false)

watch(() => props.isArchiveDrawerOpen, (val) => {
    isDrawerOpen.value = val
})

watch(isDrawerOpen, (val) => {
    emit('update:isArchiveDrawerOpen', val)
})

const triggerFileInput = () => {
    fileInput.value.click();
}

const handleFileChange = (event) => {
  const selectedFile = event.target.files[0]
  if (selectedFile) {
    files.value = [selectedFile]
  }
}

const removeFile = (index) => {
    files.value.splice(index, 1);
}


const loading = ref(false)

const handleArchiveFormSubmit = async () => {
    await submitArchiveForm({
        onSuccess: () => {
            emit('archiveAdded')
        }
    })
}

const docTypeOptions = [
    { id: 'SB', name: 'SB' },
    { id: 'PDC', name: 'PDC' },
    { id: 'SIP', name: 'SIP' }
]


// watch(() => plantillaForm.odsu_id, (newVal) => {
//     if (newVal) {
//         filteredPositions.value = positions.value.filter(p => p.odsu_id === newVal)
//     }
// })

// watch(() => props.plantillaToEdit, (newVal) => {
//     filteredPositions.value = positions.value.filter(p => p.odsu_id === newVal)
//     if (newVal) {
//         plantillaForm.odsu_id = newVal.odsu
//         plantillaForm.position_id = newVal.position_id
//         plantillaForm.employee_id = newVal.employee_id
//     } else {
//         plantillaForm.odsu_id = ''
//         plantillaForm.position_id = ''
//         plantillaForm.employee_id = ''
//     }
// }, { immediate: true })

onMounted(() => {
})
</script>
