<template>
    <div class="flex flex-col gap-2">
        <Heading class="text-start">{{ fundsource?.fundsource }}</Heading>

        <div>
            <Button variant="primary" @click="showProject = !showProject">Add Project</Button>
        </div>

        <div class="w-full-overflow-auto flex gap-5">
            <ProjectStatic v-if="showProject" :fundsource="fundsource" @add="handleProjectAddition" @drawerClosed="handleCloseDrawer"></ProjectStatic>

            <ProjectDynamic v-for="project in fundsource?.projects" :key="project?.id" :project="project"></ProjectDynamic>
        </div>

        <div class="w-full overflow-x-auto">

        </div>
    </div>
</template>

<script setup>
const route = useRoute()

const fundsource = ref() 

const { api } = useAxios()

const showProject = ref(false)

onMounted(() => {
    api.get(`/api/fundsource/${route.params.slug}`).then(({ data }) => {
        fundsource.value = data.data
    })
})

function handleProjectAddition(project) {
    showProject.value = false
    fundsource.value.projects.push(project)
}

function handleCloseDrawer(){
    showProject.value = false
}
</script>