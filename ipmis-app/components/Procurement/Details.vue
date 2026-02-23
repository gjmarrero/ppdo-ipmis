<template>
    <Dialog :open="props.isProcurementDialogOpen" @update:open="(val) => emit('update:isProcurementDialogOpen', $val)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Procurement Details</DialogTitle>
            </DialogHeader>
            <div class="relative w-full p-10">
                <Carousel class="max-w-md w-full mx-auto" :opts="{
                    align: 'start',
                }">
                    <CarouselContent>
                        <CarouselItem v-for="(timeline, index) in procurementDetails.project_timelines">
                            <div class="flex items-center space-x-2">
                                <ul class="my-6 ml-6 list-disc [&li] :mt-2">
                                    <li class="flex items-center gap-2">
                                        <h4 class="scroll-m-20 text-l font-semibold tracking-tight">Bid Opening:</h4>
                                        <span>{{ timeline.bid_evaluation_start }}</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <h4 class="scroll-m-20 text-l font-semibold tracking-tight">Post Qualification:
                                        </h4>
                                        <span>{{
                                            timeline.post_qualification_start
                                            }}</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <h4 class="scroll-m-20 text-l font-semibold tracking-tight">Bid Evaluation:</h4>
                                        <span>{{
                                            timeline.bid_evaluation
                                            }}</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <h4 class="scroll-m-20 text-l font-semibold tracking-tight">Notice of Award:
                                        </h4>
                                        <span>{{
                                            timeline.award_notice_start
                                            }}</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <h4 class="scroll-m-20 text-l font-semibold tracking-tight">Notice to Proceed:
                                        </h4>
                                        <span>{{
                                            timeline.proceed_notice_start
                                            }}</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <h4 class="scroll-m-20 text-l font-semibold tracking-tight">NTP Received by
                                            Bidder
                                        </h4>
                                        <span>{{
                                            timeline.proceed_notice_end
                                            }}</span>
                                    </li>
                                </ul>
                            </div>
                        </CarouselItem>
                    </CarouselContent>
                    <CarouselPrevious />
                    <CarouselNext />
                </Carousel>
            </div>
            <Separator />
            <DialogFooter>
                <Button @click="emit('update:isProcurementDialogOpen', false)">Close</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import Carousel from '../ui/carousel/Carousel.vue'
import CarouselContent from '../ui/carousel/CarouselContent.vue'
import CarouselItem from '../ui/carousel/CarouselItem.vue'
import CarouselNext from '../ui/carousel/CarouselNext.vue'
import CarouselPrevious from '../ui/carousel/CarouselPrevious.vue'

const { api } = useAxios()

const props = defineProps({
    funded_project: Object,
    isProcurementDialogOpen: Boolean
})

const emit = defineEmits(['update:isProcurementDialogOpen'])

const procurementDetails = ref([])

const project_title = props.funded_project.project.title

const fetchProcurementDetails = async (project_title) => {
    const response = await api.get(`/api/project_plan/${project_title}`, {
        headers: {
            Accept: 'application/json'
        }
    })
    procurementDetails.value = response.data
}

onMounted(() => {
    fetchProcurementDetails(project_title)
})
</script>

<style lang="scss" scoped></style>