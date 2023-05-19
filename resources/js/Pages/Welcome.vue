<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Sveiki" />
    <div
        class="bg-center bg-gray-100 selection:bg-gray-200 selection:text-emerald-700 transition-opacity ease-in delay-100 min-h-screen flex flex-col">
        <header class="flex items-center justify-between p-6">
            <h1 class="text-left font-extrabold text-lg">Mupro</h1>
            <div v-if="canLogin" class="text-right">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="font-semibold text-emerald-700 hover:text-emerald-800 focus:outline focus:outline-2 focus:rounded-sm focus:outline-emerald-800">
                Valdymas
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="font-semibold text-emerald-700 hover:text-emerald-800 focus:outline focus:outline-2 focus:rounded-sm focus:outline-emerald-800">
                    Prisijungti
                    </Link>
                    <Link v-if="canRegister" :href="route('register')"
                        class="ml-4 font-semibold text-emerald-700 hover:text-emerald-800 focus:outline focus:outline-2 focus:rounded-sm focus:outline-emerald-800">
                    Registruotis
                    </Link>
                </template>
            </div>
        </header>
        <div class="flex-grow flex items-center justify-center pb-32">
            <div class="text-center">
                <h1 class="text-6xl font-extrabold text-emerald-700">Mupro</h1>
                <p class="pt-4">
                    Muzikos produkcijos mokymosi platformos prototipas.
                </p>
            </div>
        </div>
    </div>
</template>
