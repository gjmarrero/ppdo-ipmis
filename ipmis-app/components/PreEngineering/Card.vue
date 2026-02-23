<template>
    <div>
        <Card
            :class="cn('w-full flex flex-col justify-between border-jetblack bg-cardbg text-textprimary', $attrs.class ?? '')">
            <CardHeader>
                <CardTitle>
                    <div class="flex justify-between items-center w-full border-b border-cardheaderborder pb-4">
                        Pre-Engineering Status
                    </div>
                </CardTitle>
                <CardDescription v-if="hasNoPreEngineering">No data available</CardDescription>
            </CardHeader>
            <div v-if="!hasNoPreEngineering">
                <CardContent class="grid gap-4">
                    <div v-for="(preengineering, index) in funded_project?.preengineering"
                        class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Programmer
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ preengineering?.employee }}
                            </p>
                        </div>
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Indicator
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ preengineering?.indicator }}
                            </p>
                        </div>

                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Date of inspection
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ preengineering?.date_conducted }}
                            </p>
                        </div>

                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Programmed Cost
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ preengineering?.programmed_cost }}
                            </p>
                        </div>

                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Current Status
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ preengineering?.latest_activity?.activity }}
                            </p>
                        </div>
                        <PreEngineeringActivity v-model:isActivityDialogOpen="isActivityDialogOpen"
                            @submitted="returnToPreEngineeringDetailsDrawer" :preengineering="preengineering" />
                    </div>
                </CardContent>
            </div>
            <CardFooter>
                <!-- <Button v-if="!hasNoPreEngineering" class="w-full" @click="$emit('openPreEngDetailDrawer', funded_project)">
                    <CircleEllipsis />View More
                </Button>
                <Button v-else-if="user.role==='peoPlanning'" variant="secondary" @click="$emit('openPreEngFormDrawer', funded_project)" class="w-full">
                    <DraftingCompass class="w-4 h-4 mr-2" /> Input Pre-engineering Status
                </Button> -->
            </CardFooter>
        </Card>
    </div>
</template>

<script setup>
import { cn } from '@/lib/utils'
import { CircleEllipsis, DraftingCompass } from 'lucide-vue-next'
const { user } = useAuth()
const props = defineProps({
    funded_project: Object
})

const emit = defineEmits(['openPreEngFormDrawer', 'openPreEngDetailDrawer'])

const hasNoPreEngineering = computed(() => {
    return (props.funded_project?.preengineering?.length ?? 0) === 0
})

</script>

<style lang="scss" scoped></style>