<template>
    <div class="flex flex-col gap-y-6">
        <div v-for="(step, index) in steps" :key="index" class="relative flex gap-x-4 items-start">
            <!-- Step indicator -->
            <div class="flex flex-col items-center">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold" :class="{
                    'bg-primary text-white': step.state === 'active',
                    'bg-muted text-muted-foreground': step.state === 'completed',
                    'border border-input text-gray-400': step.state === 'inactive'
                }">
                    <Button shape="circle"
                        :variant="step.state === 'completed' || step.state === 'active' ? 'primary' : 'secondary'"
                        size="icon" class="z-10 rounded-full shrink-0" :class="[
                            step.state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background',
                            step.state === 'completed' && 'bg-green-600 text-indigo'
                        ]">
                        <Check v-if="step.state === 'completed'" />
                        <Circle v-else-if="step.state === 'active'" />
                        <Dot v-else />
                    </Button>
                </div>
                <div v-if="index < steps.length - 1" class="w-px flex-1 bg-border mt-1"></div>
            </div>

            <!-- Step content -->
            <div class="flex flex-col" :class="{
                'opacity-50': step.state === 'completed',
                'opacity-100': step.state === 'active' || step.state === 'inactive'
            }">
                <div class="text-sm font-medium">{{ step.title }}</div>
                <div class="text-xs text-muted-foreground">{{ step.description }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Check, Circle, Dot } from 'lucide-vue-next';

defineProps({
    steps: {
        type: Array,
        required: true
    }
})
</script>
