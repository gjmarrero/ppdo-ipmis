<template>
    <div>
        <Card :class="cn('w-full mb-2 border-jetblack bg-cardbg text-textprimary', $attrs.class ?? '')">
            <CardHeader>
                <CardTitle>
                    <div class="flex justify-between items-center w-full border-b border-cardheaderborder pb-4">
                        Project Description
                    </div>
                </CardTitle>
                <CardDescription></CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid w-full grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="grid grid-cols-[25px_minmax(0,1fr)] items-start">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Title
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.project?.title }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-[25px_minmax(0,1fr)] items-start">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Cost
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ Number(funded_project.approved_cost).toLocaleString('en-US', {
                                    minimumFractionDigits: 2, maximumFractionDigits: 2
                                }) }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-[25px_minmax(0,1fr)] items-start">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Location
                            </p>
                            <p v-for="(location, index) in funded_project?.project?.locations" :key="index"
                                class="text-sm text-muted-foreground">
                                {{
                                    [location?.sitio, location?.barangay, location?.municipality]
                                        .filter(Boolean) // remove null, undefined, or empty strings
                                        .join(', ')
                                }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-[25px_minmax(0,1fr)] items-start">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Fund Source
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.fundsource }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-[25px_minmax(0,1fr)] items-start">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Project Number
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.number }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-[25px_minmax(0,1fr)] items-start">
                        <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                        <div class="space-y-1 mb-5">
                            <p class="text-sm font-medium leading-none">
                                Reference Number
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ funded_project?.reference_number }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row flex-grow w-full">
                    <div v-if="funded_project?.latest_site_problem" class="flex flex-row w-full mb-4">
                        <AlertNotice variant="info" title="Info">{{ funded_project?.latest_site_problem?.problem_type }}
                            <span
                                v-if="funded_project?.latest_site_problem?.problem_type === 'Change Of Project Title'">
                                : From {{ funded_project?.initial_title }} To {{ funded_project?.title }} </span>
                            <span v-if="funded_project?.latest_site_problem?.problem_type === 'Additional Funding'"> :
                                {{ Number(funded_project?.latest_site_problem?.additional_fund).toLocaleString('en-US',
                                    {
                                        minimumFractionDigits: 2, maximumFractionDigits: 2
                                }) }}
                            </span>
                            <p> Status: {{ funded_project?.latest_site_problem?.pdc_result ?? 'Awaiting PDC' }}</p>
                            <p> Supplemental Budget No.: {{ funded_project?.latest_site_problem?.archive?.number }}</p>
                        </AlertNotice>
                    </div>
                </div>
            </CardContent>
            <CardFooter>
            </CardFooter>
        </Card>
    </div>
</template>

<script setup>
import { cn } from '@/lib/utils'
const props = defineProps({
    funded_project: Object
})
</script>

<style lang="scss" scoped></style>