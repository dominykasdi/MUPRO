<script setup>
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { Head } from '@inertiajs/vue3';
import DashboardCard from '@/Components/DashboardCard.vue';
</script>

<template>
    <Head title="Mokinio valdymas" />

    <LearnerLayout>
        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg text-xl text-left">
                    <div class="p-6 text-gray-900">Sveiki prisijungę, mokiny!
                        <span class="font-semibold text-emerald-700">
                            Ką norite daryti?
                        </span>
                    </div>
                </div>
                <div class="text-center text-3xl font-semibold text-emerald-700 m-8">Pasirinkite</div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <DashboardCard title="Visi kursai"
                        description="Peržiūrėkite visus galimus kuratorių sukurtus kursus ir išsirinkite, kuriuose norite dalyvauti."
                        link="/mokinys/kursai">
                        <template #icon>
                            <i class="fas fa-book text-emerald-700 text-2xl"></i>
                        </template>
                    </DashboardCard>

                    <DashboardCard title="Mano įsiregistravimai"
                        description="Raskite visus savo įsiregistruotus kursus vienoje vietoje."
                        link="/mokinys/kursai/isiregistravimai">
                        <template #icon>
                            <i class="fas fa-user-check text-emerald-700 text-2xl"></i>
                        </template>
                    </DashboardCard>
                    <DashboardCard title="Perklausos"
                        description="Siųskite savo kūrinių demo įrašus kuratoriams ir gaukite jų atsiliepimą."
                        link="/perklausos">
                        <template #icon>
                            <i class="fas fa-music text-emerald-700 text-2xl"></i>
                        </template>
                    </DashboardCard>
                </div>
            </div>
        </div>
    </LearnerLayout>
</template>


