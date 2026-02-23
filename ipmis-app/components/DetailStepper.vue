<template>
    <div class="w-full flex flex-grow items-start">
        <Stepper v-if="stepsMap[generic_length?.id]?.length" class="flex w-full items-start gap-2">
            <StepperItem v-for="(step, index) in stepsMap[generic_length.id]" :key="step.step" :data-state="step.state"
                v-slot="{ state }" :step="step.step" class="relative flex w-full flex-col items-center justify-center">

                <StepperSeparator v-if="index < stepsMap[generic_length.id].length - 1"
                    class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary" />
                <StepperTrigger as-child>
                    <Button shape="circle"
                        :variant="step.state === 'completed' || step.state === 'active' ? 'primary' : 'secondary'"
                        size="sm" class="z-10 rounded-full shrink-0" :class="[
                            step.state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background',
                            step.state === 'completed' && 'bg-green-600 text-indigo'
                        ]">
                        <Check v-if="step.state === 'completed'" />
                        <Circle v-else-if="step.state === 'active'" />
                        <Dot v-else />
                    </Button>
                </StepperTrigger>
                <div class="mt-5 flex flex-col items-center text-center">
                    <StepperTitle :class="[step.state === 'active' && 'text-primary']"
                        class="text-sm font-semibold transition md:text-base">
                        {{ step.title }}
                    </StepperTitle>
                    <StepperDescription :class="[step.state === 'active' && 'text-primary']"
                        class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm">
                        {{ step.description }}
                    </StepperDescription>
                </div>
            </StepperItem>
        </Stepper>
    </div>
</template>

<script setup>
import { Check, Circle, Dot } from 'lucide-vue-next';
import Stepper from './ui/stepper/Stepper.vue';
import StepperItem from './ui/stepper/StepperItem.vue';
import StepperSeparator from './ui/stepper/StepperSeparator.vue';
import StepperTrigger from './ui/stepper/StepperTrigger.vue';
import Button from './Button.vue';
import StepperTitle from './ui/stepper/StepperTitle.vue';
import StepperDescription from './ui/stepper/StepperDescription.vue';



const props = defineProps({
    stepsMap: Object,
    generic_length: Object,

})

</script>

<style lang="scss" scoped></style>