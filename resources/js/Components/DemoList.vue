<script setup>
import { ref, defineProps, defineEmits } from "vue";
import Modal from "./Modal.vue";
import PrimaryButton from "./PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";
import axios from 'axios';

const props = defineProps({
    demos: Array,
    fetchDemos: Function,
});

const emit = defineEmits(["update-demo-list"]);

const selectedDemo = ref(null);
const showModal = ref(false);

const openModal = (demo) => {
    selectedDemo.value = demo;
    showModal.value = true;
};

const closeModal = () => {
    selectedDemo.value = null;
    showModal.value = false;
};

const feedback = ref('');

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
};

const submitFeedback = async () => {
    if (!feedback.value) {
        alert("Prašome užpildyti atsiliepimo lauką prieš pateikiant.");
        return;
    }
    try {
        const response = await axios.patch(`/kuratorius/perklausos/${selectedDemo.value.id}`, {
            feedback: feedback.value,
            is_checked: 1,
        });

        if (response && response.data && response.data.success) {
            alert("Atsiliepimas pateiktas sėkmingai!");
            feedback.value = "";
            closeModal();
            emit('update-demo-list');
        } else {
            alert("Nepavyko pateikti atsiliepimo.");
        }
    } catch (error) {
        console.error("Error submitting feedback:", error);
        alert("Nepavyko pateikti atsiliepimo.");
    }
};
</script>

<template>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="(demo, index) in demos" :key="index"
                class="bg-white p-4 space-y-2 rounded-lg shadow-md cursor-pointer transition duration-300 hover:shadow-lg"
                @click="openModal(demo)">
                <div>
                    <span class="font-normal text-black text-sm">Perklausai pateikė</span>
                    <p class="font-semibold text-emerald-700 text-md">{{ demo.learner.name }}</p>
                </div>
                <div>
                    <span class="font-normal text-black text-sm">Demo įrašo pavadinimas</span>
                    <p class="font-semibold text-emerald-700">{{ demo.demo_title }}</p>
                </div>
                {{ demo.file_name }}
                <div class="text-xs text-gray-500">
                    {{ new Date(demo.created_at).toLocaleString() }}
                </div>
            </div>
        </div>

        <Modal v-if="selectedDemo && !selectedDemo.is_checked" :show="showModal" @close="closeModal">
            <div class="m-6">
                <div class="mb-4">

                    <p class="text-gray-700 font-normal text-xs pb-2">Pateikta
                    <p class="font-semibold">{{ formatDate(selectedDemo.created_at) }}</p>
                    </p>

                    <p class="text-black font-semibold text-sm">Demo pavadinimas
                    <p class="font-semibold text-emerald-700 text-lg">{{ selectedDemo.demo_title }}</p>
                    </p>

                    <div class="mt-3 mb-3 border-l-4 pl-2 border-emerald-700">
                        <h2 class="text-sm font-semibold text-black" v-if="selectedDemo.learner">Perklausai pateikė
                            <p class="font-semibold text-emerald-700 text-sm">{{ selectedDemo.learner.name }}</p>
                        </h2>
                    </div>

                    <p class="text-black font-semibold text-lg">Mokinio žinutė
                    <p class="font-normal text-base pt-1">{{ selectedDemo.note }}</p>
                    </p>

                    <div class="pt-4">
                        <hr>
                    </div>
                </div>
                <div class="mb-4 pt-2">
                    <h3 class="text-lg font-semibold mb-2 text-emerald-700">Perklausai pateiktas demo įrašas</h3>
                    <audio controls :src="selectedDemo.file_path" class="min-w-full">
                        Jūsų žiniatinklio naršyklė nepalaiko garso įrašo (audio) elemento.
                    </audio>
                </div>

                <div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-emerald-700">Parašykite atsiliepimą</h3>
                        <textarea v-model="feedback"
                            class="mt-1 focus:ring-emerald-500 w-full focus:border-emerald-500 block h-32 shadow-sm sm:text-sm border-gray-300 rounded-md resize-none text-gray-700"
                            placeholder="Jūsų atsiliepimas.."></textarea>
                    </div>
                    <div class="flex justify-between mt-4">
                        <PrimaryButton type="button" @click="submitFeedback">
                            Pateikite atsiliepimą
                        </PrimaryButton>
                        <SecondaryButton type="button" @click="closeModal">
                            Uždaryti
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal v-if="selectedDemo && selectedDemo.is_checked" :show="showModal" @close="closeModal">
            <div class="m-6">
                <div class="mb-4">

                    <p class="text-gray-700 font-normal text-xs pb-2">Pateikta
                    <p class="font-semibold">{{ formatDate(selectedDemo.created_at) }}</p>
                    </p>

                    <p class="text-black font-semibold text-sm">Demo pavadinimas
                    <p class="font-semibold text-emerald-700 text-lg">{{ selectedDemo.demo_title }}</p>
                    </p>

                    <div class="mt-3 mb-3 border-l-4 pl-2 border-emerald-700">
                        <h2 class="text-sm font-semibold text-black" v-if="selectedDemo.learner">Perklausai pateikė
                            <p class="font-semibold text-emerald-700 text-sm">{{ selectedDemo.learner.name }}</p>
                        </h2>
                    </div>

                    <p class="text-black font-semibold text-lg">Mokinio žinutė
                    <p class="font-normal text-base pt-1">{{ selectedDemo.note }}</p>
                    </p>

                    <div class="pt-4">
                        <hr>
                    </div>
                </div>
                <div class="mb-4 pt-2">
                    <h3 class="text-lg font-semibold mb-2 text-emerald-700">Perklausai pateiktas demo įrašas</h3>
                    <audio controls :src="selectedDemo.file_path" class="min-w-full">
                        Jūsų žiniatinklio naršyklė nepalaiko garso įrašo (audio) elemento.
                    </audio>
                </div>
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-2 text-emerald-700">Jūsų atsiliepimas</h3>
                        <div class="text-black rounded-lg">{{ selectedDemo.feedback }}</div>
                    </div>
                    <div class="flex justify-end">
                        <SecondaryButton type="button" @click="closeModal">
                            Uždaryti
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>
