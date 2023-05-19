<script setup>
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import Show from '@/Pages/Courses/Steps/Curator/Show.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import EditStepModal from '@/Pages/Courses/Steps/Curator/EditStepModal.vue';
import EditCourseModal from './EditCourseModal.vue';
import { ref, computed, watch } from 'vue';
import CourseReview from '@/Components/CourseReview.vue';

const { course: courseProps, steps } = usePage().props;
const course = ref(courseProps);
const reload = ref(false);

const removeStep = (deletedStepId) => {
    const index = steps.findIndex((step) => step.id === deletedStepId);
    if (index > -1) {
        steps.splice(index, 1);
    }
    for (let i = index; i < steps.length; i++) {
        steps[i].order--;
    }
};

const editCourseModalOpen = ref(false);
const deleteCourseModalOpen = ref(false);

const openEditCourseModal = () => {
    editCourseModalOpen.value = true;
};

const updateCourse = (updatedCourse) => {
    course.value = { ...course.value, ...updatedCourse };
};

const openDeleteCourseModal = () => {
    deleteCourseModalOpen.value = true;
};

const form = {
    data: ref({}),
    errors: ref({}),
    processing: ref(false),
};

const deleteCourse = () => {
    router.post(route('courses.curator.destroy', { course: course.value.id }), form.data.value, {
        preserveScroll: true,
    });
};

const navigateToAddEditSteps = () => {
    router.get(route('courses.steps.create', { course: course.value.id }));
};

const addEditButtonText = computed(() => {
    return steps.length > 0 ? 'Pridėti daugiau dalių' : 'Pridėti dalis';
});

const editingStep = ref(null);

const editStep = (step) => {
    editingStep.value = step;
};

const updateStep = (updatedStep) => {
    const index = steps.findIndex((step) => step.id === updatedStep.id);
    if (index > -1) {
        steps[index] = { ...steps[index], ...updatedStep };
    }
    reload.value = true;
};

watch(editingStep, (newValue, oldValue) => {
    if (oldValue !== null && newValue === null && reload.value) {
        location.reload();
    }
});

</script>

<style scoped>
.step {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
}
</style>

<template>
    <Head :title="` ${course.title}`" />
    <CuratorLayout>
        <div class="container mx-auto px-4 pb-32">
            <div>
                <h1 class="text-center text-emerald-700 font-bold text-3xl mt-6">Kurso peržiūra</h1>
                <div class="max-w-3xl mx-auto mt-10">
                    <div class="bg-gray-100 shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 text-black">

                        <h2 class="text-lg text-emerald-700 font-semibold">Kurso pavadinimas</h2>
                        <h2 class="text-xl mb-6">{{ course.title }}</h2>

                        <p class="mb-1 text-lg text-emerald-700 font-semibold">Kurso aprašymas</p>
                        <p class="mb-4">{{ course.description }}</p>

                        <p class="mb-1 text-lg text-emerald-700 font-semibold">Kurso žymos</p>
                        <div class="mb-4">
                            <span v-for="(tag, index) in course.tags" :key="index"
                                class="mr-2 text-xs border-emerald-700 rounded-md p-1 bg-emerald-700 text-white">
                                {{ tag.name }}
                            </span>
                        </div>

                        <div>
                            <div class="mt-6 mb-1 border-l-4 pl-2 border-emerald-700">
                                <p class="text-lg text-emerald-700">Aktyvių įsiregistravimų</p>
                                <p class="font-semibold">{{ course.enrolled_learners_count }}</p>
                            </div>
                        </div>

                        <div class="pt-6 flex justify-between">

                            <PrimaryButton @click.stop="openEditCourseModal"
                                class="text-white font-semibold text-sm bg-emerald-700 hover:bg-emerald-800">
                                Redaguoti Kursą
                            </PrimaryButton>

                            <DangerButton @click.stop="openDeleteCourseModal">Ištrinti Kursą
                            </DangerButton>

                            <Modal :show="deleteCourseModalOpen" @close="() => (deleteCourseModalOpen = false)">
                                <form @submit.prevent="deleteCourse" class="p-6">
                                    <h2 class="text-lg font-semibold text-emerald-700">
                                        Ar tikrai norite ištrinti šį kursą?
                                    </h2>
                                    <p>Ištrynus kursą, visi su juo susiję duomenys taip pat bus ištrinti. Kurso mokiniai
                                        nebematys šio kurso.</p>

                                    <div class="mt-6 flex justify-end">
                                        <SecondaryButton @click="() => (deleteCourseModalOpen = false)"> Atšaukti
                                        </SecondaryButton>

                                        <DangerButton class="ml-3" :class="{ 'opacity-25': form.processing.value }"
                                            :disabled="form.processing.value" type="submit">Ištrinti kursą</DangerButton>

                                    </div>
                                </form>
                            </Modal>

                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <h3 class="text-xl mb-4 mt-7 font-semibold text-black">Kurso dalys</h3>

                        <div class="pl-2 pt-3">
                            <PrimaryButton class="text-xs" @click="navigateToAddEditSteps">{{ addEditButtonText }}
                            </PrimaryButton>
                        </div>

                    </div>
                    <div v-if="steps.length > 0" class="pt-5">
                        <Show v-for="(step, index) in steps" :key="index" :step="step" :course="course"
                            class="bg-gradient-to-r from-teal-600 to-emerald-700 shadow-lg" @delete-step="removeStep"
                            @edit-step="editStep" />

                        <EditStepModal :isOpen="editingStep !== null" :step="editingStep" :course="course"
                            @close-modal="editingStep = null" @updated="updateStep" />

                        <EditCourseModal :isOpen="editCourseModalOpen" :course="course"
                            @close-modal="editCourseModalOpen = false" @updated="updateCourse" />

                    </div>
                    <div v-else class="border-l-4 border-emerald-700 rounded-r-lg w-52 text-sm">
                        <p class="ml-2 font-normal text-black">Kursas neturi pridėtų dalių.</p>
                    </div>

                    <CourseReview :course-id="course.id" :initial-reviews="course.reviews || []"
                        :disableReviewSubmission="true" />

                </div>
            </div>
        </div>
    </CuratorLayout>
</template>