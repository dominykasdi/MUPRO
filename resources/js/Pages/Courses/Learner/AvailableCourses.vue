<script setup>
import LearnerLayout from "@/Layouts/LearnerLayout.vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TagsClickable from "@/Components/TagsClickable.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from "@/Components/Modal.vue";
import { ref, computed } from "vue";

const { props } = usePage();
const { courses, enrolledCourses, tags } = props;
const sortOrder = ref("newest");
const showEnrollModal = ref(false);
let selectedCourse = ref(null);

const form = ref({
    selectedTags: [],
});

const sortedCourses = computed(() => {
    if (sortOrder.value === "newest") {
        return courses.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } else if (sortOrder.value === "oldest") {
        return courses.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    }
});

const filteredCourses = computed(() => {
    let courses = sortedCourses.value;

    if (form.value.selectedTags.length !== 0) {
        courses = courses.filter(course => {
            return form.value.selectedTags.every(tagId => course.tags.some(tag => tag.id === tagId));
        });
    }

    return courses;
});

const dropdownVisible = ref(false);

const toggleDropdown = () => {
    dropdownVisible.value = !dropdownVisible.value;
};

const closeDropdown = () => {
    dropdownVisible.value = false;
};

const enroll = (course) => {
    showEnrollModal.value = true;
    selectedCourse.value = course;
};

const confirmEnroll = async () => {
    await router.post(route("courses.enroll", { course: selectedCourse.value.id }), {
        onSuccess: () => {
            router.get(route("enrollments.learner.index"));
        },
    });
    showEnrollModal.value = false;
};

const isEnrolled = (courseId) => {
    return enrolledCourses.some((enrollment) => enrollment.course_id === courseId);
};

</script>

<template>
    <Head title="Visi pasiekiami kursai" />

    <LearnerLayout>
        <div class="text-center">
            <h1 class="text-emerald-700 font-semibold text-3xl mt-6">Visi pasiekiami kursai</h1>
            <p class=" text-emerald-800 mt-4 text-md"><span class="font-semibold">Čia matomi visi pasiekiami
                    kursai.</span> Norėdami prisijungti prie kurso, spauskite <span
                    class="font-semibold uppercase text-emerald-700">ĮSIREGISTRUOTI</span>.</p>
            <p class=" text-emerald-800  text-md mt-1"><span class="font-semibold">
                    Kursus, į kuriuos esate įsiregistravęs, galite rasti skiltyje </span>
                <Link href="/mokinys/kursai/isiregistravimai"
                    class="font-semibold uppercase text-emerald-700 underline underline-offset-4 hover:text-emerald-800">
                Mano įsiregistravimai</Link>.
            </p>
        </div>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="pt-8 flex items-center justify-end space-x-6">
                    <div class="relative inline-block text-left">
                        <button type="button"
                            class="rounded-md inline-flex justify-center items-center text-xs shadow-md px-2 py-1 bg-emerald-700 hover:bg-teal-800"
                            id="filter-menu" aria-haspopup="true" aria-expanded="true" @click="toggleDropdown">
                            Filtruoti pagal žymas
                        </button>
                        <div v-show="dropdownVisible"
                            class="rounded-lg origin-top-left absolute left-0 mt-2 w-72 shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="filter-menu">
                                <div class="flex justify-between items-center px-2 py-2">
                                    <div>
                                        <TagsClickable :tags="tags || []" v-model="form.selectedTags"
                                            class="inline justify-evenly" />
                                    </div>
                                    <button @click="closeDropdown" class="focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <InputLabel for="sortOrder" class="text-sm block font-medium text-gray-700">Rikiuoti pagal:
                        </InputLabel>
                        <select v-model="sortOrder" id="sortOrder"
                            class="text-xs text-black form-select block w-46 h-9 ml-2 rounded-md shadow-md">
                            <option value="newest">Naujausius</option>
                            <option value="oldest">Seniausius</option>
                        </select>
                    </div>
                </div>

                <div class="pt-8">
                    <div class="overflow-hidden rounded-lg">
                        <div class="text-gray-900">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="course in filteredCourses" :key="course.id"
                                    class="bg-white overflow-hidden shadow-lg rounded-lg p-4">
                                    <Link :href="route('courses.view', course.id)"
                                        class="text-emerald-700 hover:text-emerald-900 hover:border-b hover:border-black font-semibold text-xl">
                                    {{ course.title }}
                                    </Link>
                                    <p class="mt-2 text-black">
                                        {{
                                            course.description.length > 100
                                            ? course.description.substring(0, 100) + "..."
                                            : course.description
                                        }}
                                    </p>

                                    <div class="mt-4 flex">
                                        <PrimaryButton v-if="!isEnrolled(course.id)" @click="enroll(course)">
                                            Įsiregistruoti
                                        </PrimaryButton>
                                        <span v-else
                                            class="bg-emerald-100 rounded-r-lg border-l-4 pl-2 p-2 border-emerald-700 text-black font-semibold text-sm">Į
                                            ŠĮ KURSĄ JAU ĮSIREGISTRUOTA</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LearnerLayout>

    <Modal :show="showEnrollModal" @close="showEnrollModal = false">
        <div class="p-6">
            <h2 class="font-semibold text-lg mb-4">Ar tikrai norite įsiregistruoti į šį kursą?</h2>
            <div class="flex justify-end">
                <PrimaryButton @click="confirmEnroll" class="mr-2">Taip</PrimaryButton>
                <SecondaryButton @click="showEnrollModal = false">Ne</SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
  