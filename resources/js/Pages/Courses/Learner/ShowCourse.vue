<script setup>
import LearnerLayout from "@/Layouts/LearnerLayout.vue";
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CourseReview from "@/Components/CourseReview.vue";

const { props } = usePage();
const { course, enrolledCourses } = props;

const enroll = (course) => {
    if (confirm("Ar tikrai norite įsiregistruoti į šį kursą?")) {
        router.post(route("courses.enroll", { course: course.id }), {
            onSuccess: () => {
                router.get(route("enrollments.learner.index"));
            },
        });
    }
};

const goToCourse = () => {
    const enrollment = enrolledCourses.find(e => e.course_id === course.id);
    if (enrollment) {
        router.get(route('enrollments.learner.enrolledCourse', { enrollment: enrollment.id }));
    } else {
        console.error("Enrollment not found");
    }
};

const isEnrolled = (courseId) => {
    return enrolledCourses.some((enrollment) => enrollment.course_id === courseId);
};

</script>

<template>
    <Head :title="` ${course.title}`" />
    <LearnerLayout>
        <div class="container mx-auto px-4">
            <h1 class="text-center text-emerald-700 font-bold text-3xl mt-4">Dalinė kurso peržiūra</h1>
            <div class="max-w-3xl mx-auto mt-10">
                <div class="text-left pb-6">
                    <Link href="/mokinys/kursai"
                        class="text-center text-emerald-700 font-semibold hover:underline underline-offset-8 hover:text-emerald-800">
                    <i class="fa-solid fa-arrow-left pr-2"></i> Grįžti į
                    visų kursų puslapį</Link>
                </div>
                <div class="bg-gray-100 shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 text-black justify-evenly">
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

                    <div class="border-l-4 border-emerald-700 rounded-r-lg pl-2 text-sm">
                        <p class="mb-4 font-semibold">Bendras dalių skaičius šiame kurse: {{ course.steps.length }}</p>
                    </div>

                    <div class="mt-6">
                        <hr>
                        <h2 class="font-semibold pb-3 pt-5">Pirmoji kurso dalis</h2>
                        <h3 class="text-lg text-emerald-700">{{ course.steps[0].title }}</h3>
                        <p class="mt-2 text-gray-600">{{ course.steps[0].description }}</p>
                        <div v-if="course.steps[0].youtube_video_id" class="mt-4">
                            <iframe class="w-full aspect-video shadow-lg rounded-lg"
                                :src="'https://www.youtube.com/embed/' + course.steps[0].youtube_video_id" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>

                        <p class="mt-5 text-sm text-emerald-700">Kurso kuratorius</p>
                        <p class="mb-5 font-semibold text-sm">{{ course.curator.name }}</p>

                        <div class="mt-6">
                            <PrimaryButton v-if="!isEnrolled(course.id)" @click="enroll(course)">
                                Įsiregistruoti
                            </PrimaryButton>
                            <div v-else class="flex justify-between">
                                <span
                                    class="bg-emerald-100 rounded-r-lg border-l-4 pl-2 p-2 border-emerald-700 text-black font-semibold text-sm">
                                    Į ŠĮ KURSĄ JAU ĮSIREGISTRUOTA
                                </span>
                                <PrimaryButton @click="goToCourse">Eiti į kursą</PrimaryButton>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <details>
                            <summary class="text-lg text-emerald-700 font-semibold hover:text-emerald-900 cursor-pointer">
                                Kitos kurso dalys</summary>
                            <div v-for="(step, index) in course.steps.slice(1)" :key="index" class="mt-8">
                                <h3 class="font-semibold text-lg text-gray-800">{{ step.order }}. {{ step.title }}</h3>
                                <p class="mt-2 text-gray-600">{{ step.description }}</p>
                                <div class="mt-4 bg-gradient-to-r from-emerald-600 to-teal-700 shadow-lg rounded-lg p-4">
                                    <p class="text-white" v-if="!isEnrolled(course.id)">Dalies turinys pasiekiamas
                                        įsiregistravus į kursą.</p>
                                    <p class="text-white" v-else>Norėdami matyti visą turinį, spauskite mygtuką <span
                                            class="font-semibold">EITI Į KURSĄ</span>.</p>
                                </div>
                            </div>
                        </details>
                    </div>
                </div>

                <CourseReview :course-id="course.id" :initial-reviews="course.reviews || []" />

            </div>
        </div>
    </LearnerLayout>
</template>



  