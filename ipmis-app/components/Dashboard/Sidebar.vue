<template>
    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-drawer text-textprimary px-6 pb-4">
            <NuxtLink to="/" class="flex items-center gap-2 pt-6">
                <img src="/logo.png" class="h-20 w-20" alt="Logo" />
                <span class="font-bold text-4xl text-textsecondary font-talina">BIPMS</span>
            </NuxtLink>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-1">
                    <!-- <li>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li v-for="(item, index) in navigation" :key="item.name">
                                <NuxtLink :to="item.href" @click="setActive(index)"
                                    :class="[item.current ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']">
                                    <component :is="item.icon"
                                        :class="[item.current ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600', 'h-6 w-6 shrink-0']"
                                        aria-hidden="true" />
                                    <span
                                        :class="[item.current ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600', 'h-6 shrink-0']">{{
                                            item.name }}</span>
                                </NuxtLink>
                            </li>
                        </ul>
                        
                    </li> -->

                    <li v-for="(item, index) in navigation" :key="item.name">
                        <div v-if="item.children">
                            <button @click="toggleDropdown(item.name)"
                                class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-textsecondary hover:bg-gray-50 hover:text-yellow-700">
                                <component :is="item.icon"
                                    class="h-6 w-6 shrink-0 text-textsecondary group-hover:text-yellow-700"
                                    aria-hidden="true" />
                                <span class="flex-1 text-left">{{ item.name }}</span>
                                <svg :class="['w-4 h-4 transition-transform', openDropdown === item.name ? 'rotate-180' : '']"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul v-if="openDropdown === item.name" class="ml-6 mt-1 space-y-1">
                                <li v-for="child in item.children" :key="child.name">
                                    <NuxtLink :to="child.href" :class="[
                                        route.path === child.href
                                            ? 'bg-gray-50 text-yellow-700'
                                            : 'text-textsecondary hover:bg-gray-50 hover:text-yellow-700',
                                        'group flex gap-x-3 rounded-md p-2 text-sm font-medium'
                                    ]">
                                        <span>{{ child.name }}</span>
                                    </NuxtLink>
                                </li>
                            </ul>
                        </div>

                        <div v-else>
                            <NuxtLink :to="item.href" @click="setActive(index)" :class="[
                                route.path === item.href
                                    ? 'bg-gray-50 text-yellow-700'
                                    : 'text-textsecondary hover:bg-gray-50 hover:text-yellow-700',
                                'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6'
                            ]">
                                <component :is="item.icon" :class="[
                                    route.path === item.href
                                        ? 'text-yellow-700'
                                        : 'text-gray-400 group-hover:text-yellow-700',
                                    'h-6 w-6 shrink-0'
                                ]" aria-hidden="true" />
                                <span>{{ item.name }}</span>
                            </NuxtLink>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</template>

<script setup>

const { navigation, userRole } = useSidebar()

const route = useRoute()

const setActive = (index) => {
    navigation.value.forEach((item) => (item.current = false))
    navigation.value[index].current = true;
}

const openDropdown = ref(null)

const toggleDropdown = (name) => {
    openDropdown.value = openDropdown.value === name ? null : name
}

onMounted(() => {
    navigation.value.forEach(item => {
        if (item.children?.some(child => route.path.startsWith(child.href))) {
            openDropdown.value = item.name
        }
    })
})
</script>

<style lang="scss" scoped></style>