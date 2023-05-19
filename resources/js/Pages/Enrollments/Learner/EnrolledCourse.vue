<script setup>
import LearnerLayout from "@/Layouts/LearnerLayout.vue";
import ShowSteps from '@/Pages/Courses/Steps/Learner/ShowSteps.vue';
import { usePage, Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const { course, steps, enrollment } = usePage().props;

const courseRef = ref(course);

const showConfirmModal = ref(false);
const markSuccess = ref(false);

function toggleConfirmModal() {
    showConfirmModal.value = true;
}

async function confirmMarkAsComplete() {
    try {
        await markCourseAsComplete();
        markSuccess.value = true;

        setTimeout(() => {
            showConfirmModal.value = false;
            markSuccess.value = false;
        }, 2000);
    } catch (error) {
        console.error(error);
    }
}

async function markCourseAsComplete() {
    try {
        toggleConfirmModal();

        const response = await axios.post(`/mokinys/${courseRef.value.enrollment_id}/mark-as-complete`);
        if (response.data.message) {
            courseRef.value.completed = true;
        }
    } catch (error) {
        console.error(error);
    }
}
</script>

<template>
    <Head :title="` ${course.title}`" />
    <LearnerLayout>
        <div class="container mx-auto px-4">
            <h1 class="text-center text-emerald-700 font-bold text-3xl mt-4">Įsiregistruoto kurso peržiūra</h1>
            <div class="max-w-3xl mx-auto mt-10">
                <div class="text-left pb-6">
                <Link href="/mokinys/kursai/isiregistravimai"
                    class="text-center text-emerald-700 font-semibold hover:underline underline-offset-8 hover:text-emerald-800"><i class="fa-solid fa-arrow-left pr-2"></i> Grįžti į
                įsiregistravimus</Link>
            </div>
                <div class="bg-gray-100 shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 text-black justify-evenly">

                    <template v-if="course.completed">
                        <div class="pb-5">
                            <p
                                class="bg-emerald-100 rounded-r-lg border-l-4 pl-2 p-2 border-emerald-700 text-black font-semibold text-sm uppercase">
                                ✓ Kursas baigtas.</p>
                        </div>
                    </template>

                    <h3 class="text-lg text-emerald-700 font-semibold">Kurso pavadinimas</h3>
                    <h2 class="text-xl mb-6">{{ course.title }}</h2>

                    <p class="mb-1 text-lg text-emerald-700 font-semibold">Kurso aprašymas</p>
                    <p class="mb-4">{{ course.description }}</p>

                    <p class="mt-1 text-lg text-emerald-700 font-semibold">Kurso žymos</p>
                    <div class="mb-6">
                        <span v-for="(tag, index) in course.tags" :key="index"
                            class="mr-2 text-xs border-emerald-700 rounded-md p-1 bg-emerald-700 text-white">
                            {{ tag.name }}
                        </span>
                    </div>

                    <div class="border-l-4 border-emerald-700 rounded-r-lg pl-1 text-sm">
                        <p class="mb-4 font-semibold">Bendras dalių skaičius šiame kurse: {{ course.steps.length }}</p>
                    </div>

                    <p class="mt-5 text-sm text-emerald-700">Kurso kuratorius</p>
                    <p class="font-semibold text-sm">{{ course.curator.name }}</p>
                </div>

                <div>
                    <div class="flex justify-between">
                        <h3 class="text-xl mb-4 mt-7 font-semibold text-black">Kurso dalys</h3>
                    </div>


                    <div v-if="steps.length > 0" class="pt-5">
                        <ShowSteps v-for="(step, index) in steps" :key="index" :step="step" :course="course"
                            :enrollmentId="enrollment.id"
                            class="bg-gradient-to-r from-teal-600 to-emerald-700 shadow-lg cursor-pointer" />
                    </div>
                    <PrimaryButton @click="toggleConfirmModal" v-if="!course.completed"
                        class="mt-5 mb-10 bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded flex">
                        Pažymėti kursą kaip baigtą
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </LearnerLayout>

    <Modal :show="showConfirmModal" @close="showConfirmModal = false">
        <div class="p-6">
            <div v-if="!markSuccess">
                <h2 class="font-semibold text-lg mb-4">Ar tikrai norite pažymėti šį kursą kaip baigtą?</h2>
                <div class="flex justify-end">
                    <PrimaryButton @click="confirmMarkAsComplete" class="mr-2">Taip</PrimaryButton>
                    <SecondaryButton @click="showConfirmModal = false">Ne</SecondaryButton>
                </div>
            </div>
            <div v-else>
                <h2 class="font-semibold text-lg mb-4 text-emerald-700">✓ Kursas pažymėtas kaip baigtas.</h2>
            </div>
        </div>
    </Modal>
</template>