<script setup>
import { ref, computed } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const { props } = usePage();
const enrollments = ref(props.enrollments);
const showUnenrollModal = ref(false);
let selectedEnrollment = ref(null);
const unenrollSuccess = ref(false);

const displayEnrollments = computed(() => {
    return enrollments.value.map(enrollment => ({
        ...enrollment,
        status: enrollment.completed ? '✓ Kursas baigtas' : 'Kursas vykdomas',
    }));
});

const sortOrder = ref("newest");

const sortedEnrollments = computed(() => {
    let sorted = [];

    if (sortOrder.value === "newest") {
        sorted = displayEnrollments.value.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } else if (sortOrder.value === "oldest") {
        sorted = displayEnrollments.value.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    } else if (sortOrder.value === "completed") {
        sorted = displayEnrollments.value.filter(enrollment => enrollment.completed);
    } else if (sortOrder.value === "ongoing") {
        sorted = displayEnrollments.value.filter(enrollment => !enrollment.completed);
    }

    return sorted;
});

const goToCourse = (enrollment) => {
    router.get(route('enrollments.learner.enrolledCourse', { enrollment: enrollment.id }));
};

const unenroll = (enrollment) => {
    showUnenrollModal.value = true;
    selectedEnrollment.value = enrollment;
};

const confirmUnenroll = async () => {
    await router.delete(route('enrollments.learner.unenroll', { enrollment: selectedEnrollment.value.id }));
    unenrollSuccess.value = true;

    const index = enrollments.value.findIndex(e => e.id === selectedEnrollment.value.id);
    if (index !== -1) {
        enrollments.value.splice(index, 1);
    }

    setTimeout(() => {
        showUnenrollModal.value = false;
        unenrollSuccess.value = false;
    }, 2000);
};
</script>

<template>
    <Head title="Mano kursai" />
    <LearnerLayout>
        <div class="text-center">
            <h1 class="text-emerald-700 font-semibold text-3xl mt-6">Mano įsiregistravimai</h1>
            <p class=" text-emerald-800 mt-4 text-md font-semibold">
                Čia matomi visi kursai, į kuriuos esate įsiregistravęs.
            </p>
            <p class=" text-emerald-800 mt-1 text-md">
                Kitus kursus, į kuriuos galite įsiregistruoti, rasite skiltyje
                <Link href="/mokinys/kursai"
                    class="font-semibold uppercase text-emerald-700 underline underline-offset-4 hover:text-emerald-800">
                Visi kursai</Link>.
            </p>
        </div>

        <div class="py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class=" space-y-4">

                            <div class="flex items-center justify-end pb-4">
                                <InputLabel for="sortOrder" class="text-sm block font-medium text-gray-700">Rikiuoti pagal:
                                </InputLabel>
                                <select v-model="sortOrder" id="sortOrder"
                                    class="text-xs text-black form-select block w-46 h-9 ml-2 rounded-md shadow-md">
                                    <option value="newest">Naujausius</option>
                                    <option value="oldest">Seniausius</option>
                                    <option value="completed">Baigti kursai</option>
                                    <option value="ongoing">Vykdomi kursai</option>
                                </select>
                            </div>

                            <div v-if="displayEnrollments.length === 0"
                                class="bg-emerald-500 shadow-lg rounded-lg p-6 text-center">
                                <h3 class="text-2xl font-semibold text-white">Dar neįsiregistravote į jokį kursą!</h3>
                                <p class="m-4 text-gray-100">Įsiregistruokite į norimus kursus ir matykite jų sąrašą čia.
                                </p>
                                <PrimaryButton @click="router.get(route('courses.available'))"
                                    class="py-2 px-4 rounded-lg bg-emerald-700 hover:bg-emerald-600">
                                    Visi kursai
                                </PrimaryButton>
                            </div>

                            <div v-else class="space-y-4">
                                <div v-for="enrollment in sortedEnrollments" :key="enrollment.id"
                                    class="bg-white overflow-hidden shadow-lg rounded-lg p-4 relative">
                                    <div class="pb-4">
                                        <p
                                            class="border-l-4 pl-2 border-emerald-700 text-black font-semibold text-sm bg-emerald-100 rounded-r-lg p-2">
                                            {{ enrollment.status }}
                                        </p>
                                    </div>
                                    <p>Kursas</p>
                                    <div v-if="enrollment.course">
                                        <Link class="text-emerald-700 font-semibold text-xl"
                                            @click="goToCourse(enrollment)">
                                        {{ enrollment.course.title }}
                                        </Link>
                                    </div>
                                    <div v-else class="font-semibold text-xl">
                                        {{ enrollment.course }}
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <PrimaryButton @click="goToCourse(enrollment)">Eiti į kursą</PrimaryButton>
                                        <DangerButton @click="unenroll(enrollment)">
                                            Palikti kursą
                                        </DangerButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LearnerLayout>

    <Modal :show="showUnenrollModal" @close="showUnenrollModal = false">
        <div class="p-6">
            <div v-if="!unenrollSuccess">
                <h2 class="font-semibold text-lg mb-4">Ar tikrai norite palikti kursą?</h2>
                <div class="flex justify-end">
                    <DangerButton @click="confirmUnenroll" class="mr-2">Taip</DangerButton>
                    <SecondaryButton @click="showUnenrollModal = false">Ne</SecondaryButton>
                </div>
            </div>
            <div v-else>
                <h2 class="font-semibold text-lg mb-4 text-emerald-700">Kursas paliktas.</h2>
            </div>
        </div>
    </Modal>
</template>