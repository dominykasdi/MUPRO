<template>
  <div class="flex flex-col bg-slate-200 text-white min-h-screen">
    <!-- Top primary navigation -->
    <nav class="bg-gradient-to-r from-emerald-700 to-emerald-600 p-4 shadow-md">
      <div class="container mx-auto">
        <div class="flex justify-between items-center">
          <Link :href="route('dashboard')">
          <h2 class="text-xl font-black text-white">
            Mupro
            <span class="text-xl font-light">
              Kuratorius
            </span>
          </h2>
          </Link>

          <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
            <NavLink :href="route('courses.curator.index')" :active="route().current('courses.curator.index')"
              class="uppercase">
              Mano kursai
            </NavLink>
            <NavLink :href="route('enrollments.curator.index')" :active="route().current('enrollments.curator.index')"
              class="uppercase">
              Įsiregistravimai
            </NavLink>
            <NavLink :href="route('demodrop.index')" :active="route().current('demodrop.index')"
              class="uppercase">
              Perklausos
            </NavLink>
          </div>


          <!-- Profile controls -->
          <div class="hidden lg:flex lg:items-center lg:ml-6">
            <!-- Settings Dropdown -->
            <div class="ml-3 relative">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <PrimaryButton type="button"
                      class="inline-flex items-center px-3 py-2 border border-transparent bg-emerald-800 text-lg leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                      {{ $page.props.auth.user.name }}
                      <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                      </svg>
                    </PrimaryButton>
                  </span>
                </template>

                <template #content>
                  <DropdownLink :href="route('profile.edit')"> Profilis </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button">
                    Atsijungti
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </div>


          <!-- Hamburger -->
          <div class="-mr-2 flex items-center lg:hidden">
            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-emerald-600 hover:bg-white focus:outline-none focus:bg-white focus:text-emerald-600 transition duration-150 ease-in-out">
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{
                  hidden: showingNavigationDropdown,
                  'inline-flex': !showingNavigationDropdown,
                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{
                  hidden: !showingNavigationDropdown,
                  'inline-flex': showingNavigationDropdown,
                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>


      <!-- Responsive Navigation Menu -->
      <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="mt-4 lg:hidden">
        <div class="pb-3 space-y-1">
          <h1 class="uppercase text-center font-semibold pb-3">Meniu</h1>
          <ul>
            <li>
              <ResponsiveNavLink :href="route('courses.curator.index')"
                :active="route().current('courses.curator.index')">
                Mano kursai
              </ResponsiveNavLink>
            </li>
            <li>
              <ResponsiveNavLink :href="route('enrollments.curator.index')"
                :active="route().current('enrollments.curator.index')">
                Įsiregistravimai
              </ResponsiveNavLink>
            </li>
            <li>
              <ResponsiveNavLink :href="route('demodrop.index')"
                :active="route().current('demodrop.index')">
                Perklausos
              </ResponsiveNavLink>
            </li>
            <!-- Add more navigation items here -->
          </ul>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 bg-emerald-800 rounded-lg">
          <div class="px-4">
            <div class="font-semibold text-lg text-white">
              {{ $page.props.auth.user.name }}
            </div>
            <div class="font-light text-sm text-gray-300">{{ $page.props.auth.user.email }}</div>
          </div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink :href="route('profile.edit')"> Profilis </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="rounded-b-lg">
              Atsijungti
            </ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main content -->
    <div class="w-full p-4">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showingNavigationDropdown = ref(false);
</script>
<style scoped>
@media (min-width: 1024px) {
  .lg\:hidden {
    display: none;
  }
}
</style>
