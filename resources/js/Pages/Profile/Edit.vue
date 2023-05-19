<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { ref } from 'vue';

const { roles } = usePage().props;

const isLearner = ref(roles.includes('learner'));

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profilis" />

    <LearnerLayout v-if="isLearner">

        <h1 class="text-center text-emerald-700 font-bold text-3xl mt-6">Profilio redagavimas</h1>

        <p class=" text-emerald-800 mt-2 text-sm text-center">Šiame puslapyje galite atnaujinti savo profilio informaciją,
            slaptažodį bei ištrinti profilį.</p>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow rounded-lg text-black">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow rounded-lg text-black">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </LearnerLayout>

    <CuratorLayout v-else>

        <h1 class="text-center text-emerald-700 font-bold text-3xl mt-6">Profilio redagavimas</h1>

        <p class=" text-emerald-800 mt-2 text-sm text-center">Šiame puslapyje galite atnaujinti savo profilio informaciją,
            slaptažodį bei ištrinti profilį.</p>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow rounded-lg text-black">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow rounded-lg text-black">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </CuratorLayout>
</template>
