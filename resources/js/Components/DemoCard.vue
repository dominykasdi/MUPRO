<script setup>
import { ref, computed } from "vue";
import PrimaryButton from "./PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";
import Modal from "./Modal.vue";

const props = defineProps({
    demoTitle: String,
    note: String,
    submissionDate: String,
    updatedAt: String,
    status: String,
    audioSrc: String,
    curatorName: String,
    feedback: String,
});

const formattedSubmissionDate = computed(() => {
    const date = new Date(props.submissionDate);
    return isNaN(date) ? "" : date.toLocaleString();
});

const showNoteModal = ref(false);
const showFeedbackModal = ref(false);

</script>

<template>
    <div class="bg-white shadow-md rounded-lg p-4 space-y-2 relative">
        <div class="text-lg font-semibold text-emerald-700">{{ demoTitle }}</div>
        <div class="text-xs text-gray-500">{{ formattedSubmissionDate }}</div>
        <div v-if="curatorName" class="text-sm text-gray-900">Kuratorius <p class="font-semibold">{{ curatorName }}</p>
        </div>

        <div class="text-sm text-gray-900">
            <p class="pb-1">Jūsų žinutė</p>
            <button @click="showNoteModal = true"
                class="text-sm font-semibold rounded-md text-emerald-700 hover:underline underline-offset-4 hover:text-emerald-800">
                Peržiūrėti žinutę
            </button>
        </div>

        <div class="text-sm text-gray-900">Būsena <p class="font-semibold text-emerald-700">{{ status }}</p>
        </div>

        <div class="pt-2 bg-gradient-to-r from-emerald-700 to-emerald-600 p-3 rounded-md shadow-md">
            <p class="text-white text-sm pb-2 font-semibold">Demo įrašas</p>
            <audio controls :src="audioSrc" class="w-full"></audio>
        </div>

        <div class="pt-2">
            <PrimaryButton v-if="feedback" @click="showFeedbackModal = true">
                Atsiliepimo peržiūra
            </PrimaryButton>
            <div v-else class="bg-red-100 rounded-r-lg border-l-4 pl-ą p-2 border-red-400 text-black font-semibold text-sm">
                Kuratorius dar nepateikė atsiliepimo.
            </div>
        </div>
    </div>

    <Modal :show="showNoteModal" @close="showNoteModal = false">
        <div class="p-6">
            <p class="text-black font-semibold text-sm">Demo pavadinimas</p>
            <h3 class="text-lg text-emerald-700 font-semibold mb-4">{{ demoTitle }}</h3>
            <h2 class="font-semibold text-lg">Jūsų žinutė</h2>
            <p>{{ note }}</p>
        </div>
    </Modal>

    <Modal :show="showFeedbackModal" @close="showFeedbackModal = false">
        <div class="p-8">
            <h3 class="text-emerald-700 font-semibold">{{ demoTitle }}</h3>
            <p>{{ updatedAt }}</p>
            <h3 class="text-xl font-semibold mb-3">
                Kuratoriaus atsiliepimas
            </h3>
            <p>{{ feedback }}</p>
            <div class="flex justify-end">
                <SecondaryButton @click="showFeedbackModal = false" class="mt-4">
                    Uždaryti
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
