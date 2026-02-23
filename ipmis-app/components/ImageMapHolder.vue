<template>
    <Dialog :open="props.isOpen" @update:open="(val) => emit('update:isOpen', val)">
        <DialogContent class="w-full sm:max-w-[100px] md:max-w-[1400px] bg-platinum border border-jetblack">
            <DialogHeader>
                <DialogTitle>Image and Map</DialogTitle>
            </DialogHeader>
            <div class="flex items-center space-x-2">
                <div class="flex justify-center w-1/2 p-10">
                    <Carousel v-model="currentIndex">
                        <CarouselContent>
                            <template v-for="(image, index) in images" :key="index">
                                <CarouselItem>
                                    <div class="flex flex-col items-center">
                                        <img :src="image?.image_path" alt="Project Image"
                                            class="max-w-full max-h-[70vh] rounded-lg shadow" />


                                    </div>
                                </CarouselItem>

                            </template>
                        </CarouselContent>
                        <CarouselPrevious v-if="images.length > 1" />
                        <CarouselNext v-if="images.length > 1" />
                    </Carousel>
                </div>
                <Separator orientation="vertical" />
                <div class="flex justify-center w-1/2">
                    <ClientOnly>
                        <!-- <LeafletMap :markers="[images[currentIndex]]" /> -->
                        
                        <MapboxMap :markers="[images[currentIndex]]" :zoom="13" height="400px" />
                    </ClientOnly>
                </div>
            </div>
        </DialogContent>
        <DialogFooter>

        </DialogFooter>
    </Dialog>
</template>

<script setup>

const props = defineProps({
    images: Object,
    isOpen: Boolean
})

const emit = defineEmits(['update:isOpen'])

const currentIndex = ref(0)

onMounted(() => {
    console.log("Dialog mounted")
    console.log("ImagesD", props.images)
    console.log("Mapbox Token:", import.meta.env.NUXT_PUBLIC_MAPBOX_ACCESS_TOKEN)
})
</script>

<style lang="scss" scoped></style>