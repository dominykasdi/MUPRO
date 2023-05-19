<script setup>
import { onMounted, ref, computed } from "vue";
import { router, Head } from "@inertiajs/vue3";
import axios from "axios";
import LearnerLayout from "@/Layouts/LearnerLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DemoCard from '@/Components/DemoCard.vue';
import InputLabel from "@/Components/InputLabel.vue";

const demos = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get("/mokinys/perklausos");
        if (response && response.data) {
            if (Array.isArray(response.data.data) && response.data.data.length) {
                demos.value = response.data.data;
            } else {
                console.log("No demo drops available.");
            }
        } else {
            console.error("Error: Response data is missing or undefined.");
        }
    } catch (error) {
        console.error("Error fetching demo drops:", error);
    }
});

const sortOrder = ref("newest");

const sortedDemos = computed(() => {
    let sorted = [];

    if (sortOrder.value === "newest") {
        sorted = demos.value.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } else if (sortOrder.value === "oldest") {
        sorted = demos.value.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    }

    return sorted.map((demo) => {
        return {
            ...demo,
            status: demo.is_checked ? "Perklausyta" : "Laukiama perklausos",
        };
    });
});

const createDemo = () => {
    router.get("/mokinys/perklausos/sukurti");
};
</script>

<template>
    <Head title="Perklausos" />
    <LearnerLayout>
        <div class="text-center">
            <h2 class="text-emerald-700 font-semibold text-3xl mt-6">Perklausos</h2>
            <div class="mt-4 text-emerald-800">
                <p class="mb-4">Norėdami išsiųsti naują demo įrašą perklausai, spauskite mygtuką <span
                        class="font-semibold uppercase">naujas demo</span>.</p>
                <PrimaryButton class="bg-emerald-700 text-white px-4 py-2 rounded-md" @click="createDemo">
                    Naujas demo įrašas
                </PrimaryButton>
            </div>
        </div>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-center pb-4">
                    <h2 class="text-emerald-700 font-semibold text-2xl mt-6">Jūsų išsiųsti demo įrašai</h2>
                </div>
                <div class="flex items-center justify-end space-x-6 pb-4">
                    <InputLabel for="sortOrder" class="text-sm block font-medium text-gray-700">Rikiuoti pagal:
                    </InputLabel>
                    <select v-model="sortOrder" id="sortOrder"
                        class="text-xs text-black form-select block w-46 h-9 ml-2 rounded-md shadow-md">
                        <option value="newest">Naujausius</option>
                        <option value="oldest">Seniausius</option>
                    </select>
                </div>
                <div v-if="demos.length" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <DemoCard v-for="(demo, index) in sortedDemos" :key="index" :demoTitle="demo.demo_title"
                        :note="demo.note" :submissionDate="demo.created_at" :status="demo.status" :audioSrc="demo.file_path"
                        :curatorName="demo.curator_name" :feedback="demo.feedback" />
                </div>

                <div v-else class="bg-emerald-500 shadow-lg rounded-lg p-6 text-center max-w-4xl mx-auto mt-4">
                    <h3 class="text-2xl font-semibold text-white">Dar neišsiuntėte jokių demo įrašų perklausai.</h3>
                    <p class="m-4 text-gray-100">Jūsų kuratoriams išsiųsti demo įrašai bus matomi čia.</p>
                    <PrimaryButton @click="createDemo" class="py-2 px-4 rounded-lg bg-emerald-700 hover:bg-emerald-600">
                        Naujas demo įrašas
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </LearnerLayout>
</template>