<template>
    <div :class="[
        'flex items-start gap-3 rounded-lg p-4',
        variantClasses.container
    ]">
        <component :is="variantClasses.icon" class="h-6 w-6 shrink-0" :class="variantClasses.iconColor" />
        <div>
            <h3 class="font-semibold" :class="variantClasses.textColor">
                {{ title }}
            </h3>
            <p class="text-sm" :class="variantClasses.textColor">
                <slot>{{ message }}</slot>
            </p>
        </div>
    </div>
</template>

<script setup>
import { AlertTriangle, XCircle, Info, CheckCircle2 } from "lucide-vue-next";

const props = defineProps({
    title: { type: String, default: "Notice" },
    message: { type: String, default: "" },
    variant: {
        type: String,
        default: "warning",
    },
});

const variantMap = {
    warning: {
        container: "border border-yellow-400 bg-yellow-50",
        textColor: "text-yellow-800",
        iconColor: "text-yellow-600",
        icon: AlertTriangle,
    },
    error: {
        container: "border border-red-400 bg-red-50",
        textColor: "text-red-800",
        iconColor: "text-red-600",
        icon: XCircle,
    },
    info: {
        container: "border border-blue-400 bg-blue-50",
        textColor: "text-blue-800",
        iconColor: "text-blue-600",
        icon: Info,
    },
    success: {
        container: "border border-green-400 bg-green-50",
        textColor: "text-green-800",
        iconColor: "text-green-600",
        icon: CheckCircle2,
    },
};

const variantClasses = computed(() => variantMap[props.variant]);
</script>
