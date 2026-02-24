<template>

  <div class="bg-mainbg">

    <DashboardSidebarMobile />

    <DashboardSidebar />

    <div class="lg:pl-72 flex flex-col min-h-screen">
      <div
        class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 px-4 bg-mainbg text-textprimary shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
        <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
          <span class="sr-only">Open sidebar</span>
          <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>

        <div class="h-6 w-px bg-mainbg lg:hidden" aria-hidden="true" />

        <div class="flex justify-between items-center w-full px-4 py-2">
          <div class="flex items-center gap-2 text-left">
            <h3 class="text-2xl sm:text-xl md:text-2xl lg:text-3xl font-semibold tracking-tight text-textsecondary font-quintessential">
              <span class="sm:hidden">BIPMS</span>
              <span class="hidden sm:inline">
                Benguet Infrastructure Project Monitoring System
              </span>
            </h3>
          </div>
          <div class="flex items-center gap-2">
            <p>{{ currentUser?.name || 'Loading...' }}</p>
            <Menu as="div" class="relative">
              <MenuButton class="-m-1.5 flex items-center p-1.5">
                <span class="sr-only">Open user menu</span>
                <img class="h-8 w-8 rounded-full bg-gray-50"
                  src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                  alt="" />
                <span class="hidden lg:flex lg:items-center">
                  <ChevronDownIcon class="ml-2 h-5 w-5 text-gray-400" aria-hidden="true" />
                </span>
              </MenuButton>
              <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <MenuItems
                  class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                  <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                  <NuxtLink v-if="item.href != '/logout'" :to="item.href"
                    :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900 text-center']">
                    {{ item.name }}</NuxtLink>
                  <Button variant="ghost" @click="logout" v-else
                    :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']">{{
                      item.name }}</Button>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>

      <main class="flex-1 py-0">
        <div class="px-0 sm:px-6 lg:px-0 ">
          <NuxtPage />
        </div>
      </main>

      <footer
        class="sticky bottom-0 bg-mainbg px-4 py-4 sm:px-6 lg:px-8 text-sm text-textmuted flex justify-between items-center">
        <span>PGO-MIS|Marrero GJ.</span>
        <span class="font-medium text-textmuted">© {{ new Date().getFullYear() }} Benguet IPMIS. All rights
          reserved.</span>
      </footer>
    </div>
  </div>
</template>

<script setup>

import {
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from '@headlessui/vue'
import {
  Bars3Icon,
  BellIcon,
} from '@heroicons/vue/24/outline'
import { ChevronDownIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid'

const userNavigation = [
  { name: 'My account', href: '/dashboard/settings/account' },
  { name: 'Sign out', href: '/logout' },
]

const { sidebarOpen } = useSidebar()

const { logout, user, me } = useAuth()

const currentUser = computed(() => user.value)

onMounted(() => {
  me()
})

definePageMeta({
  middleware: ['auth']
})


</script>