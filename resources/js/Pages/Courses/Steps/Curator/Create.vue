<script setup>
import { ref } from 'vue';
import { usePage, router, Head, useForm } from '@inertiajs/vue3';
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const steps = ref([{ title: '', description: '', youtube_video_id: '', order: '' }]);
const { course, existingSteps } = usePage().props;

const form = useForm({ steps: steps.value });

const addStep = () => {
    steps.value.push({ title: '', description: '', youtube_video_id: '', order: '' });
};

const submitForm = () => {
    form.steps = form.steps.map((step) => {
        return {
            ...step,
            order: parseInt(step.order),
        };
    });

    form.post(`/kuratorius/kursai/${course.id}/dalys`, {
        preserveScroll: true,
        onSuccess: () => {
            steps.value = [{ title: '', description: '', youtube_video_id: '', order: '' }];
            router.push({ name: 'courses.curator.show', params: { course: course.id } });
        },
    });
};
</script>

<template>
    <CuratorLayout>

        <Head :title="` Dalių pridėjimas / ${course.title}`" />
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto mt-10">
                <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
                    <h2 class="text-2xl mb-6 font-semibold text-gray-800">Sukurti kurso dalis</h2>
                    <p class="text-black pb-3 font-semibold">Kurso pavadinimas: <span class="font-light">{{ course.title
                    }}</span></p>

                    <template v-if="existingSteps.length > 0">
                        <h3 class="mb-1 text-gray-800 font-semibold">Esamos dalys</h3>
                        <ol>
                            <li v-for="(step, index) in existingSteps" :key="step.id" class="mb-1 text-emerald-700 text-sm">
                                {{ step.order }}. {{ step.title }}
                            </li>
                        </ol>
                    </template>

                    <form @submit.prevent="submitForm">
                        <template v-for="(step, index) in form.steps" :key="index">
                            <div class="mb-10">
                                <h3 class="mb-4 mt-5 text-gray-800 font-semibold">Nauja dalis</h3>
                                
                                <div class="mb-4">
                                    <InputLabel for="order" class="block text-sm font-bold mb-2">
                                        Dalies eiliškumas
                                    </InputLabel>
                                    <TextInput v-model="step.order" type="number" id="order" name="order" min="1"
                                        placeholder="Dalies eiliškumas.."
                                        class="text-gray-700 shadow appearance-none border rounded-lg w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" />
                                    <InputError class="mt-2"
                                        :message="form.errors[`steps.${index}.order`] 
                                        ? (form.errors[`steps.${index}.order`][0] === 
                                        'Toks dalies eiliškumas jau egzistuoja.' 
                                        ? 'Dalies eiliškumas būtinas.' 
                                        : 'Dalies eiliškumas būtinas arba toks eiliškumas jau egzistuoja.') : ''" />
                                </div>

                                <div class="mb-4">
                                    <InputLabel for="title" class="block text-sm font-bold mb-2">
                                        Dalies pavadinimas
                                    </InputLabel>
                                    <TextInput v-model="step.title" type="text" id="title" name="title"
                                        placeholder="Dalies pavadinimas.."
                                        class="text-gray-700 shadow appearance-none border rounded-lg w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" />
                                    <InputError class="mt-2"
                                        :message="form.errors[`steps.${index}.title`] ? 'Dalies pavadinimas būtinas.' : ''" />
                                </div>

                                <div class="mb-4">
                                    <InputLabel for="description" class="block text-gray-700 text-sm font-bold mb-2">
                                        Dalies aprašymas
                                    </InputLabel>
                                    <textarea v-model="step.description" id="description" name="description"
                                        placeholder="Dalies aprašymas.."
                                        class="mt-1 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    <InputError class="mt-2"
                                        :message="form.errors[`steps.${index}.description`] ? 'Dalies aprašymas būtinas.' : ''" />

                                </div>

                                <div class="mb-4">
                                    <InputLabel class="block text-gray-700 text-sm font-bold mb-2" for="youtube_video_id">
                                        YouTube vaizdo įrašo ID <span class="text-emerald-700">(testinis ID: 8UX_Gsj2NgE)</span>
                                    </InputLabel>
                                    <TextInput
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        id="youtube_video_id" type="text" v-model="step.youtube_video_id"
                                        name="youtube_video_id" placeholder="Įveskite YouTube vaizdo įrašo ID" />
                                    <InputError class="mt-2"
                                        :message="form.errors[`steps.${index}.youtube_video_id`] ? 'YouTube vaizdo įrašo ID būtinas.' : ''" />
                                </div>
                            </div>
                        </template>

                        <div class="mb-4">
                            <button @click.prevent="addStep" class="text-emerald-700 font-semibold hover:text-emerald-800">
                                + Pridėti dar vieną dalį
                            </button>
                        </div>

                        <div class="flex items-center justify-between">
                            <PrimaryButton type="submit" class="">
                                Išsaugoti
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </CuratorLayout>
</template>
  