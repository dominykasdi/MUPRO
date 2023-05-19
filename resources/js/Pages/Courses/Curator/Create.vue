<script setup>
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    tags: Array,
})

const form = useForm({
    title: '',
    description: '',
    selectedTags: [],
});

function submitForm() {
    form.post(route('courses.store'), {
        onSuccess: () => {
            form.reset();
            router.visit(route('courses.steps.index', course.id));
        },
    });
}
</script>

<template>
    <Head title="Sukurti naują kursą" />
    <CuratorLayout>
        <div class="text-center">
            <h1 class="text-emerald-700 font-semibold text-3xl mt-6">Sukurti naują kursą</h1>
            <p class=" text-emerald-800 mt-2 text-sm">
                Šiame lange galite sukurti naują kursą,
                pridėdami jo aprašymą bei žymas,
                po kuriomis kursas bus matomas.</p>
        </div>

        <div class="py-10">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

                <div class="text-left pb-6">
                    <Link href="/kuratorius/kursai"
                        class="text-center text-emerald-700 font-semibold hover:underline underline-offset-8 hover:text-emerald-800">
                    <i class="fa-solid fa-arrow-left pr-2"></i> Grįžti į
                    kursų puslapį</Link>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submitForm">
                            <div>
                                <InputLabel for="title" class="block font-medium text-gray-700">Naujo kurso pavadinimas
                                </InputLabel>
                                <TextInput id="title" v-model="form.title" type="text"
                                    class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                <InputError class="mt-2"
                                    :message="form.errors.title ? 'Kurso pavadinimas privalomas.' : ''" />

                            </div>
                            <div class="mt-4">
                                <InputLabel for="description" class="block font-medium text-gray-700">Naujo kurso aprašymas
                                </InputLabel>
                                <textarea id="description" v-model="form.description" rows="3"
                                    class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                <InputError class="mt-2"
                                    :message="form.errors.description ? 'Kurso aprašymas privalomas.' : ''" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="tags" class="block font-medium text-gray-700">Žymos (žanrai, sritis)
                                </InputLabel>
                                <p class="text-xs text-emerald-700">
                                    Pasirinkti daugiau galite paspaudę
                                    <span class="font-semibold">CTRL (arba CMD) + kairį pelės mygtuką</span>.
                                </p>
                                <select id="tags" v-model="form.selectedTags" multiple
                                    class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option v-for="tag in tags" :key="tag.id" :value="tag.id">
                                        {{ tag.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2"
                                    :message="form.errors.selectedTags ? 'Privaloma parinkti bent vieną žymą.' : ''" />
                            </div>

                            <div class="mt-5 -mb-1">
                                <p class="text-sm font-medium">Paspaudus žemiau esantį mygtuką <span
                                        class="text-xs text-white font-semibold bg-emerald-700 border rounded p-1">TOLIAU</span>,
                                    sukursite
                                    naują kursą ir būsite nukreipti prie naujo kurso dalių pridėjimo.</p>
                            </div>
                            <div class="mt-4 flex space-x-4">
                                <PrimaryButton type="submit" class="inline-flex items-center px-4 py-2">
                                    Toliau
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </CuratorLayout>
</template>
  