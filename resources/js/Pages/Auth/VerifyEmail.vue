<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="El. pašto patvirtinimas" />

        <h2 class="text-center text-xl text-emerald-700 pt-4 pb-8">Patvirtinkite el. paštą</h2>

        <div class="mb-4 text-sm text-gray-600">
            Ačiū, kad užsiregistravote! Prieš pradėdami, patvirtinkite savo el. pašto adresą spustelėdami nuorodą, kuriame ką tik išsiuntėme jums el. paštu.
            Jeigu laiško negavote, be problemų atsiųsime kitą.
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
            Naujas el. pašto patvirtinimo laiškas buvo išsiųstas paštu, kurį nurodėte registravimosi metu.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Vėl siųsti patvirtinimo laišką
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >Atsijungti</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
