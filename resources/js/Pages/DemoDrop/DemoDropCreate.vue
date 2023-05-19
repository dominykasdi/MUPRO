<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { router, Head, Link } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const curators = ref([]);
const curatorId = ref('');
const demoFileInput = ref(null);
const demoFileError = ref('');
const chosenFileName = ref('');
const demo_title = ref('');
const note = ref('');
const formValid = ref(false);
const showModal = ref(false);

onMounted(async () => {
    try {
        const response = await axios.get('/demodrop/curators');
        curators.value = response.data.data;
    } catch (error) {
        console.error('Klaida sudarant kuratorių sąrašą:', error);
    }
});

const validateDemoFile = () => {
    const file = demoFileInput.value.files[0];
    if (!file) {
        demoFileError.value = 'Pasirinkite failą.';
        formValid.value = false;
        chosenFileName.value = '';
    } else if (!['audio/mp3', 'audio/wav'].includes(file.type) || file.size > 50 * 1024 * 1024) {
        demoFileError.value = 'Netinkamas failas. Galimi tik .mp3 arba .wav formato failai, kurių dydis iki 50MB';
        formValid.value = false;
        chosenFileName.value = '';
    } else {
        demoFileError.value = '';
        formValid.value = true;
        chosenFileName.value = file.name;
    }
};

const submitForm = async () => {
    const formData = new FormData();
    formData.append('curator_id', curatorId.value);
    formData.append('demo_file', demoFileInput.value.files[0]);
    formData.append('demo_title', demo_title.value);
    formData.append('note', note.value);

    try {
        await axios.post('/mokinys/perklausos', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        showModal.value = true; // Show the modal first
        setTimeout(() => {
            router.get('/perklausos'); // Then navigate to the new route after a short delay
        }, 2000);
    } catch (error) {
        console.error('Klaida talpinant demo:', error);
        if (error.response && error.response.data && error.response.data.errors) {
            const errors = error.response.data.errors;
            if (errors.demo_file) {
                demoFileError.value = errors.demo_file[0];
            }
        }
    }
};

const closeModal = () => {
    showModal.value = false;
    router.get('/perklausos');
};

</script>

<template>
    <Head title="Siųsti demo įrašą perklausai" />
    <LearnerLayout>
        <div class="p-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="text-left pb-6">
                    <Link href="/perklausos"
                        class="text-center text-emerald-700 font-semibold hover:underline underline-offset-8 hover:text-emerald-800">
                    <i class="fa-solid fa-arrow-left pr-2"></i> Grįžti į
                    perklausų pagrindinį puslapį</Link>
                </div>
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-semibold text-emerald-700">Demo įrašo siuntimas perklausai</h2>
                        <p class=" text-emerald-800 mt-2 text-sm">Užpildydami šią formą, galite išsiųsti savo sukurtą demo
                            įrašą ir gauti pasirinkto kuratoriaus atsiliepimą.</p>
                        <form class="mt-4" @submit.prevent="submitForm">
                            <div class="mb-4">
                                <InputLabel for="curator_id" class="block text-gray-700">Kuratorius</InputLabel>
                                <select id="curator_id" name="curator_id"
                                    class="text-black w-full mt-1 focus:ring-emerald-500 focus:border-emerald-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    v-model="curatorId">
                                    <option v-for="curator in curators" :value="curator.id" :key="curator.id">
                                        {{ curator.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <InputLabel for="demo_file" class="block text-gray-700">
                                    Demo įrašo failas</InputLabel>
                                <p class="text-xs text-emerald-700">
                                    Galimi tik .mp3 arba .wav formatai,
                                    <span class="font-semibold">failo dydis iki 50MB</span>.
                                </p>
                                <div class="relative mt-1">
                                    <input type="file" id="demo_file" name="demo_file" class="w-full mt-1 rounded-md hidden"
                                        accept="audio/mp3, audio/wav" ref="demoFileInput" @change="validateDemoFile" />
                                    <PrimaryButton type="button" @click="$refs.demoFileInput.click()" class="mt-1">
                                        Pasirinkti failą
                                    </PrimaryButton>
                                    <span class="ml-4 text-black text-sm" v-if="chosenFileName">{{ chosenFileName }}</span>
                                    <span class="ml-4 text-black text-sm" v-else>Nepasirinktas failas</span>
                                </div>
                                <p class="text-red-600" v-if="demoFileError">{{ demoFileError }}</p>
                            </div>

                            <div class="mb-4">
                                <InputLabel for="demo_title" class="block text-gray-700">Demo įrašo pavadinimas</InputLabel>
                                <TextInput id="demo_title" type="text" name="demo_title" v-model="demo_title"
                                    class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="note" class="block text-gray-700">Žinutė / klausimai (iki 1000 ženklų)
                                </InputLabel>
                                <textarea id="note" name="note"
                                    class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    maxlength="1000" rows="4" v-model.trim="note"></textarea>
                            </div>

                            <PrimaryButton type="submit" class="bg-emerald-700 text-white px-4 py-2 rounded-md"
                                :disabled="!formValid">
                                Įkelti ir išsiųsti demo
                            </PrimaryButton>

                            <ConfirmationModal :open="showModal" title="Demo įrašas išsiųstas perklausai"
                                message="Demo įrašas sėkmingai išsiųstas." confirmText="Gerai" cancelText="Uždaryti"
                                @confirm="closeModal" @cancel="closeModal"></ConfirmationModal>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </LearnerLayout>
</template>
  