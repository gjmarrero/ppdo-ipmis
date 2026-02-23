<template>
    <Card @click="handleClick" class="cursor-pointer hover:shadow-md transition select-none bg-cardbg text-textprimary border-jetblack">
        <CardHeader>
            <CardDescription>{{ title }}</CardDescription>
        </CardHeader>
        <CardContent>
            <p class="text-sm text-muted-foreground">{{ subtitle }}</p>
            <h2 class="text-3xl font-semibold tracking-tight">
                {{ count }}
            </h2>
        </CardContent>
    </Card>
</template>

<script setup>
    defineProps({
    title: String,
    subtitle: {
    type: String,
    default: ''
    },
    count: [Number, String],
    // onClick: Function
})

const emit = defineEmits(['card-click'])

const handleClick = () => {
  emit('card-click')
}

</script>


<style lang="scss" scoped></style>
