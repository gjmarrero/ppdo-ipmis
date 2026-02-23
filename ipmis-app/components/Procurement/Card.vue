<template>
    <div>
        <Card
            :class="cn('w-full flex flex-col justify-between border-jetblack bg-cardbg text-textprimary', $attrs.class ?? '')">
            <CardHeader>
                <CardTitle>
                    <div class="flex justify-between items-center w-full border-b border-cardheaderborder pb-4">
                        Procurement Milestone
                    </div>
                </CardTitle>
                <CardDescription v-if="!procurementDetails">No Data Available
                </CardDescription>
            </CardHeader>
            <div>
                <CardContent v-if="procurementDetails" class="grid gap-4">
                    <div class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />

                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Date Received by BAC
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ procurementDetails?.date_pow_added }}
                            </p>
                        </div>

                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Date posted
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ procurementDetails?.advertisement }}
                            </p>
                        </div>
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Pre-bid Conference
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ procurementDetails?.pre_bid }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </div>
            <!-- <CardFooter>
                <Button v-if="procurementDetails" class="w-full" @click="isProcurementDialogOpen = true">
                    <CircleEllipsis />View More
                </Button>
            </CardFooter> -->
        </Card>
    </div>
</template>

<script setup>
import { cn } from '@/lib/utils'
import { CircleEllipsis } from 'lucide-vue-next';

const { api } = useAxios()
const props = defineProps({
    project_title: String
})

const procurementDetails = ref([])

const fetchProcurementDetails = async () => {
    const response = await api.get(`/api/project_plan/${props.project_title}`, {
        headers: {
            Accept: 'application/json'
        }
    })
    procurementDetails.value = response.data.procacts[0]
}

onMounted(() => {
    fetchProcurementDetails()
})
</script>

<style lang="scss" scoped></style>