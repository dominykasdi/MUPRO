<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    description: String,
    link: String,
});

const goToLink = () => {
    router.get(props.link);
};
</script>

<template>
    <div class="bg-white p-4 rounded-lg shadow-md cursor-pointer transition duration-300 hover:shadow-lg" @click="goToLink">
        <div class="flex items-center">
            <slot name="icon"></slot>
            <h1 class="text-2xl font-semibold ml-4 text-emerald-700">{{ title }}</h1>
        </div>
        <p class="text-gray-700 mt-2">{{ description }}</p>
    </div>
</template>
  