<script setup>
import { defineProps, defineEmits, ref, computed } from 'vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    step: {
        type: Object,
        required: true,
    },
    course: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['delete-step', 'edit-step']);

const deleteStep = async () => {
    try {
        await axios.delete(`/kuratorius/kursai/dalys/${props.step.id}`);
        emit('delete-step', props.step.id);
    } catch (error) {
        console.error('Klaida trinant dali:', error);
    }
};

const editStep = () => {
    emit('edit-step', props.step);
};

const dropdownOpen = ref(false);

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const truncatedDescription = computed(() => {
    return props.step.description.length > 30 ? props.step.description.substring(0, 30) + '...' : props.step.description;
});
</script>

<template>
    <div class="step cursor-pointer" @click="toggleDropdown">
        <h3 class="text-lg font-semibold text-white">{{ step.order }}. {{ step.title }}</h3>
        <p v-if="!dropdownOpen" class="text-m text-gray-200">{{ truncatedDescription }} <span
                class="font-bold text-emerald-100">plačiau.</span></p>
        <div v-if="dropdownOpen">
            <p class="text-m text-gray-200">{{ step.description }}</p>

            <div v-if="step.youtube_video_id" class="mb-4 mt-5">
                <iframe :id="`player-${step.id}`" title="YouTube Vaizdo įrašas"
                    class="w-full h-full aspect-video shadow-lg rounded-lg" width="540" height="315"
                    :src="`https://www.youtube.com/embed/${step.youtube_video_id}?enablejsapi=1`" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
            <div class="bg-slate-100 rounded-md p-2 shadow-md">
                <div class="flex justify-between">
                    <PrimaryButton class="font-semibold text-sm bg-emerald-700 hover:bg-emerald-800" @click.stop="editStep">
                        Redaguoti dalį</PrimaryButton>
                    <PrimaryButton @click.stop="deleteStep"
                        class="text-white font-semibold text-sm ml-2 bg-rose-600 hover:bg-rose-800">Ištrinti dalį
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>
  

