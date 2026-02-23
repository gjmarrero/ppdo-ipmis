<template>
    <div>
        <ClientOnly>
            <Card :class="cn('w-full flex flex-col justify-between border-jetblack bg-cardbg text-textprimary', $attrs.class ?? '')">
                <CardHeader>
                    <CardTitle>
                        <div class="flex justify-between items-center w-full border-b border-cardheaderborder pb-4">
                            <span>Validation</span>
                        </div>
                    </CardTitle>
                    <CardDescription v-if="project?.validation_status === 'Unvalidated'">No data available
                    </CardDescription>
                </CardHeader>
                <div v-if="project?.validation_status === 'Validated'">
                    <!-- <ValidationDetails v-model:isValidationDialogOpen="isValidationDialogOpen"
                        :funded_project="funded_project" /> -->
                    <CardContent v-if="project?.validation_status === 'Validated'" class="grid gap-4">
                        <div class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0">
                            <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />

                            <div class="space-y-1 mb-5">
                                <p class="text-sm font-medium leading-none">
                                    Beneficiaries
                                </p>
                                <p v-for="(beneficiary, index) in project?.validation?.beneficiaries"
                                    class="text-sm text-muted-foreground">
                                    {{ beneficiary?.beneficiary_type }} : {{ beneficiary?.beneficiary }}
                                <p v-for="(sdd, index) in beneficiary.beneficiary_sdd"
                                    class="text-sm text-muted-foreground">
                                    Male: {{ sdd.male }}
                                    Female: {{ sdd.female }}
                                </p>
                                </p>
                            </div>

                            <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                            <div class="space-y-1 mb-5">
                                <p class="text-sm font-medium leading-none">
                                    Date of Validation
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    {{ project?.validation?.data?.date_validated }}
                                </p>
                            </div>

                            <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                            <div class="space-y-1 mb-5">
                                <p class="text-sm font-medium leading-none">
                                    Present During Inspection
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    {{ project?.validation?.data?.present_during_validation
                                    }}
                                </p>
                            </div>

                            <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                            <div class="space-y-1 mb-5">
                                <p class="text-sm font-medium leading-none">
                                    Responsible People
                                </p>
                                <p v-if="project?.validation?.responsible_people?.length" v-for="(employee, index) in project?.validation?.responsible_people" :key="employee.employee_id"
                                    class="text-sm text-muted-foreground">
                                    {{ employee.employee }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </div>
                <CardFooter>
                    <!-- <Button v-if="project?.validation_status === 'Validated'" class="w-full"
                        @click="$emit('openValidationDetailDrawer', project)">
                        <CircleEllipsis />View More
                    </Button> -->
                    <!-- <Button v-else-if="user.role === 'ppdoDevChief'" variant="secondary" class="w-full" @click="$emit('openValidationFormDrawer', project)">
                        <MapPinPlus class="w-4 h-4 mr-2" /> Validate
                    </Button> -->
                </CardFooter>

            </Card>
        </ClientOnly>
    </div>
</template>

<script setup>
import { cn } from '@/lib/utils'
import { CircleEllipsis, MapPinPlus } from 'lucide-vue-next'
const {user} = useAuth()
const props = defineProps({
    project: String
})

</script>

<style lang="scss" scoped></style>