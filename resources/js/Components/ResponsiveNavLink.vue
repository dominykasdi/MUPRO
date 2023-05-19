<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-emerald-900 shadow-lg text-left text-base font-semibold text-emerald-700 bg-gray-100 focus:outline-none focus:text-emerald-500 focus:bg-emerald-100 focus:border-emerald-700 transition duration-150 ease-in-out'
        : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-white hover:text-emerald-700 hover:bg-white hover:border-emerald-400 focus:outline-none focus:text-emerald-800 focus:bg-emerald-500 focus:border-emerald-700 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
