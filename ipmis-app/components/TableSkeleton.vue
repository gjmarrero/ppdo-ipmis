<template>
    <div class="w-full items-start">
        <Skeleton class="h-10 w-48 rounded my-4" />
        <Skeleton class="h-12 w-64 rounded my-4" />
    </div>
    <div class="flex space-x-4">
        <Skeleton class="h-6 w-1/4 rounded" />
        <Skeleton class="h-6 w-1/4 rounded" />
        <Skeleton class="h-6 w-1/4 rounded" />
        <Skeleton class="h-6 w-1/4 rounded" />
        <Skeleton class="h-6 w-1/4 rounded" />
        <Skeleton class="h-6 w-1/4 rounded" />
    </div>
    <div v-for="i in 10" :key="i" class="flex space-x-4">
        <Skeleton class="h-4 w-1/4 rounded" />
        <Skeleton class="h-4 w-1/4 rounded" />
        <Skeleton class="h-4 w-1/4 rounded" />
        <Skeleton class="h-4 w-1/4 rounded" />
        <Skeleton class="h-4 w-1/4 rounded" />
        <Skeleton class="h-4 w-1/4 rounded" />
    </div>
</template>

<script setup>
import Skeleton from './ui/skeleton/Skeleton.vue';


</script>

<style lang="scss" scoped></style>