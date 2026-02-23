<template>
    <MetaTags title="Reports" />
    <div>
        <Heading as="h3">Project List</Heading>
    </div>
    <div class="flex flex-row">
        <Button @click="generateReport">All Projects</Button>
        <Table>
            <TableHeader>
                <TableRow >
                    <TableHead>Title</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="project in projects">
                    <TableCell>{{ project.title }}</TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>

<script setup>

const { projects, fetchProjects } = useProjects()

const fetchFundedProjects = async() => {
    
}

const generateReport = async () => {
    const { api } = useAxios()

    const response = await api.get('api/reports/projects', {
        responseType: 'blob'
    })

    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'report.docx')
    document.body.appendChild(link)
    link.click()
    link.remove()

}

onMounted( () => {
    fetchProjects()
})



</script>

<style lang="scss" scoped></style>