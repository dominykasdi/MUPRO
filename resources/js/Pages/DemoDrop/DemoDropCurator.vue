<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import DemoList from '@/Components/DemoList.vue';
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import axios from 'axios';

const activeTab = ref(0);
const uncheckedDemos = ref([]);
const checkedDemos = ref([]);

const updateDemoLists = async () => {
    await fetchUncheckedDemos();
    await fetchCheckedDemos();
};

const fetchUncheckedDemos = async () => {
    try {
        const response = await axios.get('/kuratorius/perklausos/neperklausyta');
        if (response && response.data) {
            uncheckedDemos.value = response.data.data.filter(demo => demo.is_checked === 0);
        }
    } catch (error) {
        console.error('Error fetching unchecked demos:', error);
    }
};

const fetchCheckedDemos = async () => {
    try {
        const response = await axios.get('/kuratorius/perklausos/perklausyta');
        if (response && response.data) {
            checkedDemos.value = response.data.data.filter(demo => demo.is_checked === 1);
        }
    } catch (error) {
        console.error('Error fetching checked demos:', error);
    }
};

onMounted(() => {
    fetchUncheckedDemos();
    fetchCheckedDemos();
});
</script>

<template>
    <Head title="Perklausos" />
    <CuratorLayout>
        <div class="text-center">
            <h1 class="text-emerald-700 font-semibold text-3xl mt-6">Perklausos</h1>
            <p class=" text-emerald-800 mt-4 text-md">Čia matomi visi jums mokinių perklausai atsiųsti demo įrašai.</p>
        </div>
        <div class="max-w-2xl mx-auto">
            <div class="flex mt-8 bg-emerald-700 rounded-lg">
                <button :class="{ 'bg-emerald-800 active:bg-emerald-600': activeTab === 0 }"
                    class="w-1/2 text-white font-semibold py-2 rounded-lg" @click="activeTab = 0">
                    Neperklausyta
                </button>
                <button :class="{ 'bg-emerald-800': activeTab === 1 }"
                    class="w-1/2 text-white font-semibold py-2 rounded-lg" @click="activeTab = 1">
                    Perklausyta
                </button>
            </div>
        </div>

        <div v-show="activeTab === 0">
            <h1 class="text-center text-lg font-semibold text-emerald-700 mt-6">Neperklausyti demo įrašai</h1>
            <div v-if="uncheckedDemos.length === 0"
                class="bg-emerald-700 shadow-lg rounded-lg p-6 text-center max-w-3xl mx-auto mt-6">
                <h3 class="text-2xl font-semibold text-white">Nėra neperklausytų demo įrašų</h3>
                <p class="m-4 text-gray-100">Kai mokiniai atsiųs demo įrašus, jie bus rodomi čia.</p>
            </div>
            <DemoList v-else :demos="uncheckedDemos" @update-demo-list="updateDemoLists"
                class="pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8" />
        </div>
        <div v-show="activeTab === 1">
            <h1 class="text-center text-lg font-semibold text-emerald-700 mt-6">Perklausyti demo įrašai</h1>
            <div v-if="checkedDemos.length === 0"
                class="bg-emerald-500 shadow-lg rounded-lg p-6 text-center max-w-3xl mx-auto mt-6">
                <h3 class="text-2xl font-semibold text-white">Nėra perklausytų demo įrašų</h3>
                <p class="m-4 text-gray-100">Perklausius mokinio atsiųstą demo įrašą ir pateikus atsiliepimą apie jį, jis
                    atsiras čia.</p>
            </div>
            <DemoList v-else :demos="checkedDemos" @update-demo-list="updateDemoLists" :readonly="true"
                class="pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8" />
        </div>
    </CuratorLayout>
</template>
  