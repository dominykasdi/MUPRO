<template>
    <div class="max-w-3xl mx-auto mt-10 pb-12">
        <div class="bg-gray-100 shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 text-black justify-evenly">
            <div class="flex justify-between mb-6">
                <h3 class="text-lg text-emerald-700 font-semibold">Atsiliepimai apie šį kursą</h3>

                <div v-if="!disableReviewSubmission">

                    <PrimaryButton @click="isModalOpen = true"
                        class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">
                        Pridėti atsiliepimą apie šį kursą
                    </PrimaryButton>

                    <Modal :show="isModalOpen" @close="isModalOpen = false">
                        <div class="p-6">
                            <h3 class="text-lg text-emerald-700 font-semibold mb-4">Pridėti atsiliepimą apie šį kursą</h3>
                            <form @submit.prevent="submitReview">
                                <div class="mb-4">
                                    <InputLabel for="rating" class="block text-sm font-semibold">Įvertinimas (nuo 1 iki 5)
                                    </InputLabel>
                                    <TextInput type="number" name="rating" id="rating" v-model="rating" min="1" max="5"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md" />
                                </div>
                                <div class="mb-4">
                                    <InputLabel for="comment" class="block text-sm font-semibold">Komentaras</InputLabel>
                                    <textarea name="comment" id="comment" v-model="comment"
                                        class="w-full px-3 py-2 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow">
                                    </textarea>

                                </div>
                                <div class="flex justify-between">
                                    <PrimaryButton type="submit"
                                        class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">
                                        Paskelbti
                                        atsiliepimą
                                    </PrimaryButton>
                                    <SecondaryButton type="button" @click="isModalOpen = false">
                                        Atšaukti
                                    </SecondaryButton>
                                </div>
                            </form>
                        </div>
                    </Modal>
                </div>
            </div>

            <transition-group name="fade" tag="div">
                <div v-for="(review, index) in reviews.slice(0, visibleReviews)" :key="index"
                    class="mb-4 bg-gradient-to-l from-emerald-700 to-teal-600 text-white p-4 rounded-md shadow-md">
                    <div class="flex justify-between pb-2">
                        <p class="mb-1 font-semibold text-sm">Autorius: <span class="font-normal">{{ review.user.name
                        }}</span></p>
                        <p class="text-xs font-semibold">{{ formatDate(review.created_at) }}</p>
                    </div>
                    <div class="border-l-2 pl-2">
                        <p class="font-semibold text-sm">Įvertinimas: {{ review.rating }} / 5</p>
                        <p class="font-semibold text-sm pt-3">Komentaras</p>
                        <p class="text-sm">{{ review.comment }}</p>
                    </div>
                </div>
            </transition-group>

            <PrimaryButton v-if="visibleReviews < reviews.length" @click="loadMoreReviews"
                class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold rounded mt-4">
                Rodyti daugiau atsiliepimų
            </PrimaryButton>

            <p v-if="reviews.length === 0" class="italic">Dar nėra jokių atsiliepimų apie šį kursą.</p>
        </div>
    </div>
</template>
  
<script setup>
import { ref, watchEffect, defineProps } from 'vue';
import axios from 'axios';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import PrimaryButton from './PrimaryButton.vue';
import Modal from './Modal.vue';
import SecondaryButton from './SecondaryButton.vue';

const rating = ref(5);
const comment = ref('');
const initialVisibleReviews = 2;
const visibleReviews = ref(initialVisibleReviews);

const isModalOpen = ref(false);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
};

const loadMoreReviews = () => {
    visibleReviews.value += initialVisibleReviews;
}

const props = defineProps({
    courseId: Number,
    initialReviews: Array,
    disableReviewSubmission: {
        type: Boolean,
        default: false,
    },
});

const reviews = ref(props.initialReviews);

watchEffect(async () => {
    if (props.courseId) {
        const response = await axios.get(`/kursai/${props.courseId}/atsiliepimai`);
        reviews.value = response.data;
    }
});

const submitReview = async () => {
    if (rating.value === 0) {
        alert('Prašome pasirinkti įvertinimą.');
        return;
    }

    if (comment.value === '') {
        alert('Prašome parašyti komentarą.');
        return;
    }

    try {
        await axios.post(`/kursai/${props.courseId}/atsiliepimai`, {
            rating: rating.value,
            comment: comment.value,
        });
        const response = await axios.get(`/kursai/${props.courseId}/atsiliepimai`);
        reviews.value = response.data;
        rating.value = 0;
        comment.value = '';
        alert('Atsiliepimas paskelbtas sėkmingai.');
    } catch (error) {
        alert('Klaida paskelbiant atsiliepimą, bandykite dar kartą.');
    }
};
</script>
