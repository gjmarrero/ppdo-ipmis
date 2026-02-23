<template>
    <div>
        <Card :class="cn('w-full flex flex-col justify-between border-jetblack bg-cardbg text-textprimary', $attrs.class ?? '')">
            <CardHeader>
                <CardTitle>
                    <div class="flex justify-between items-center w-full border-b border-cardheaderborder pb-4">
                        Environmental Clearance
                    </div>
                </CardTitle>
                <CardDescription v-if="hasNoClearance">No Data Available</CardDescription>
            </CardHeader>
            <div v-if="!hasNoClearance">
                <EnvironmentalClearanceDetails
                    v-model:isEnvironmentalClearanceDialogOpen="isEnvironmentalClearanceDialogOpen"
                    :funded_project="funded_project" />
                <CardContent class="grid gap-4">
                    <div class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Type of Certificate
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.environmental_clearance[0]?.certificate_type }}
                            </p>
                        </div>

                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Date of ECC/CNC Application
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.environmental_clearance[0]?.date_application }}
                            </p>
                        </div>

                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Date of ECC/CNC Issued
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.environmental_clearance[0]?.date_issued }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </div>
            <CardFooter>
                <!-- <Button v-if="!hasNoClearance" class="w-full"
                    @click="isEnvironmentalClearanceDialogOpen = true">
                    <CircleEllipsis /> View More
                </Button>
                <Button v-else-if="user.role === 'benroStaff'" variant="secondary" class="w-full" @click="$emit('openEnviClearanceFormDrawer', funded_project)">
                    <Leaf class="mr-2 h-4 w-4" /> Process
                </Button> -->
            </CardFooter>
        </Card>

    </div>
</template>

<script setup>
import { cn } from '@/lib/utils'
import { Leaf, CircleEllipsis } from 'lucide-vue-next'
const { user } = useAuth();
const props = defineProps({
    funded_project: Object
})

const emits = defineEmits(['openEnviClearanceFormDrawer'])

const hasNoClearance = computed(() => {
    return (props.funded_project?.environmental_clearance?.length ?? 0) === 0
})
</script>

<style lang="scss" scoped></style>