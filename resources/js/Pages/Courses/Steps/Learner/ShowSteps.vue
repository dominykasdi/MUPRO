<script setup>
import { defineProps, ref, computed } from 'vue';

const props = defineProps({
    step: {
        type: Object,
        required: true,
    },

    course: {
        type: Object,
        required: true,
    },
    enrollmentId: {
        type: [String, Number],
        required: true,
    }
});

const dropdownOpen = ref(false);

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const truncatedDescription = computed(() => {
    return props.step.description.length > 30
        ? props.step.description.substring(0, 30) + '...'
        : props.step.description;
});

</script>
  
<style scoped>
.step {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
}
</style> 

<template>
    <div class="step" @click="toggleDropdown">
        <h3 class="text-lg font-semibold text-white">{{ step.order }}. {{ step.title }}</h3>
        <p v-if="!dropdownOpen" class="text-m text-gray-200">{{ truncatedDescription }}
            <span class="font-bold text-emerald-100">plačiau.</span>
        </p>
        <div v-if="dropdownOpen">
            <p class="text-m text-gray-200">{{ step.description }}</p>
            <div v-if="step.youtube_video_id" class="mb-4 mt-5">
                <iframe :id="`player-${step.id}`" class="w-full h-full aspect-video shadow-lg rounded-lg" width="540"
                    height="315" :src="`https://www.youtube.com/embed/${step.youtube_video_id}?enablejsapi=1`"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
</template> 