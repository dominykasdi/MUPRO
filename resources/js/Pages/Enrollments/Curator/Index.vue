<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import { computed, ref, reactive } from 'vue';

const { props } = usePage();

const enrollments = ref(props.enrollments);

const hasEnrollments = computed(() => {
    return enrollments.value.length > 0;
});

const uniqueCourses = computed(() => {
    const courseSet = new Set();
    enrollments.value.forEach((enrollment) => {
        courseSet.add(JSON.stringify(enrollment.course));
    });
    return Array.from(courseSet).map((course) => JSON.parse(course));
});

const enrollmentsByCourseId = computed(() => {
    const enrollmentsByCourse = {};
    enrollments.value.forEach((enrollment) => {
        if (!enrollmentsByCourse[enrollment.course.id]) {
            enrollmentsByCourse[enrollment.course.id] = [];
        }
        enrollmentsByCourse[enrollment.course.id].push(enrollment);
    });
    return enrollmentsByCourse;
});

const courseVisibility = reactive({});

const toggleLearners = (courseId) => {
    courseVisibility[courseId] = !courseVisibility[courseId];
};
</script>

<template>
    <Head title="Įsiregistravimai" />
    <CuratorLayout>
        <div class="text-center">
            <h1 class="text-emerald-700 font-semibold text-3xl mt-6">Įsiregistravimai</h1>
            <p class="text-emerald-800 mt-4 text-md font-semibold">
                Čia matomi įsiregistravimai į kiekvieną jūsų sukurtą kursą.
            <p class="font-normal">Dalyvaujančių mokinių sąrašas ir jų statusas matomas paspaudus <span
                    class="font-semibold">Rodyti / slėpti mokinius</span>.</p>
            </p>
        </div>

        <div class="py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div v-if="!hasEnrollments" class="bg-emerald-500 shadow-lg rounded-lg p-6 text-center">
                            <h2 class="text-2xl font-semibold text-white">Nėra jokių įsiregistravimų!</h2>
                            <p class="m-4 text-gray-100">Kai mokiniai pradės registruotis į jūsų kursus, čia matysite jų
                                sąrašą.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4">
                            <div v-for="(course, courseIndex) in uniqueCourses" :key="courseIndex"
                                class="bg-white overflow-hidden shadow-lg rounded-lg p-4 relative">
                                <p>Kursas</p>
                                <Link :href="route('courses.show', course.id)"
                                    class="text-emerald-700 hover:text-emerald-800 hover:border-b hover:border-emerald-800 font-semibold text-xl">
                                {{ course.title }}
                                </Link>
                                <div>
                                    <details>
                                        <summary @click="toggleLearners(course.id)"
                                            class=" pt-2 mt-2 text-md text-emerald-700 font-semibold cursor-pointer">
                                            Rodyti / slėpti mokinius
                                        </summary>
                                        <div v-if="courseVisibility[course.id]" class="mt-2 p-3 bg-gray-100 rounded-lg">
                                            <h3 class="text-lg font-semibold pb-2 text-emerald-700">
                                                Įsiregistravę kurso mokiniai
                                            </h3>
                                            <ul>
                                                <li v-for="(enrollment, enrollmentIndex) in enrollmentsByCourseId[course.id]"
                                                    :key="enrollmentIndex">
                                                    <span class="font-semibold">{{ enrollment.user.name }}</span> – {{
                                                        enrollment.status }}
                                                </li>
                                            </ul>
                                        </div>
                                    </details>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CuratorLayout>
</template>
  