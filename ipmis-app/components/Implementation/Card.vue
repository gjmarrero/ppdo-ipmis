<template>
    <div>
        <Card :class="cn('w-full flex flex-col justify-between border-jetblack bg-cardbg text-textprimary', $attrs.class ?? '')">
            <CardHeader>
                <CardTitle>
                    <div class="flex justify-between items-center w-full border-b border-cardheaderborder pb-4">
                        Implementation
                    </div>
                </CardTitle>
                <CardDescription v-if="!implementationDetails">No Data Available</CardDescription>
            </CardHeader>
            <CardContent class="grid gap-4">
                <div v-if="implementationDetails"
                    class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0">
                    <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                    <div class="space-y-1 mb-5">
                        <p class="text-sm font-medium leading-none">
                            Date documents received
                        </p>
                        <p class="text-sm text-muted-foreground">
                            {{ implementationDetails?.date_received }}
                        </p>
                    </div>

                    <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                    <div class="space-y-1 mb-5">
                        <p class="text-sm font-medium leading-none">
                            Date Start
                        </p>
                        <p class="text-sm text-muted-foreground">
                            {{ implementationDetails?.date_start }}
                        </p>
                    </div>

                    <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                    <div class="space-y-1 mb-5">
                        <p class="text-sm font-medium leading-none">
                            Project-In-Charge
                        </p>
                        <p class="text-sm text-muted-foreground">
                            {{ implementationDetails?.employee }}
                        </p>
                    </div>
                </div>
            </CardContent>
            <CardFooter>
                <!-- <Button @click="$emit('openImplementationDetailDrawer')" class="w-full"
                    v-if="implementationDetails">View More</Button>
                <Button v-else-if="user.role === 'peoImplementation'" variant="secondary" class="w-full"><BrickWall/>Input Implementation Details</Button> -->
            </CardFooter>
        </Card>
    </div>
</template>

<script setup>
import { cn } from '@/lib/utils'
import { BrickWall } from 'lucide-vue-next'
const { user } = useAuth()
const props = defineProps({
    implementation: Object
})

const emit = defineEmits(['openImplementationDetailDrawer'])

const implementationDetails = computed(() => {
    if (!props.implementation) return null
    return props.implementation[props.implementation.length - 1]
})
</script>

<style lang="scss" scoped></style>