<template>
    <div class="space-y-6">
        <div class="p-4 rounded-lg border space-y-4 min-h-[150px]">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <Skeleton class="h-4 w-20 mb-2" />
                    <Skeleton class="h-5 w-48" />
                </div>
                <div>
                    <Skeleton class="h-4 w-20 mb-2" />
                    <Skeleton class="h-5 w-24" />
                </div>
                <div>
                    <Skeleton class="h-4 w-20 mb-2" />
                    <Skeleton class="h-5 w-40" />
                </div>
                <div>
                    <Skeleton class="h-4 w-20 mb-2" />
                    <Skeleton class="h-5 w-32" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            
            <div class="p-4 rounded-lg border space-y-2">
                <Skeleton class="h-5 w-32" />
                <div class="space-y-2">
                    <Skeleton class="h-4 w-28" />
                    <Skeleton class="h-4 w-24" />
                    <Skeleton class="h-4 w-36" />
                </div>
                <Skeleton class="h-8 w-full mt-4" />
            </div>

            
            <div class="p-4 rounded-lg border space-y-2">
                <Skeleton class="h-5 w-40" />
                <Skeleton class="h-4 w-28" />
                <Skeleton class="h-8 w-full mt-4" />
            </div>

            
            <div class="p-4 rounded-lg border space-y-2">
                <Skeleton class="h-5 w-40" />
                <Skeleton class="h-4 w-32" />
                <Skeleton class="h-4 w-32" />
                <Skeleton class="h-4 w-36" />
            </div>

            
            <div class="p-4 rounded-lg border space-y-2">
                <Skeleton class="h-5 w-48" />
                <Skeleton class="h-4 w-32" />
                <Skeleton class="h-8 w-full mt-4" />
            </div>

            
            <div class="p-4 rounded-lg border space-y-2">
                <Skeleton class="h-5 w-40" />
                <Skeleton class="h-4 w-32" />
            </div>
        </div>
    </div>
</template>
<script setup>
import { Skeleton } from "@/components/ui/skeleton"
</script>
