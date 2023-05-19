<script setup>
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, router } from '@inertiajs/vue3';
import { defineProps, defineEmits, watch, onMounted, onUnmounted } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
    step: {
        type: Object,
        required: false,
    },
    course: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['updated', 'close-modal']);

const closeModal = () => {
    emit('close-modal');
};

const form = useForm({
    title: props.step?.title,
    description: props.step?.description,
    youtube_video_id: props.step?.youtube_video_id,
});

const submitForm = async () => {
    await form.put(route('api.steps.update', { step: props.step.id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit('updated', { ...props.step, ...form.data.value });
            closeModal();
        },
    });
};

watch(
    () => props.isOpen,
    (value) => {
        if (value && props.step) {
            form.title = props.step?.title || '';
            form.description = props.step?.description || '';
            form.youtube_video_id = props.step?.youtube_video_id || '';
            document.body.style.overflow = 'hidden';
        } else {
            form.title = '';
            form.description = '';
            form.youtube_video_id = '';
            document.body.style.overflow = null;
        }
    }
);

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.isOpen) {
        closeModal();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});
</script>

<template>
    <teleport to="body">
        <Modal :show="isOpen" @close="closeModal">
            <div class="p-2 bg-gray-100">
                <div class="bg-gray-100 rounded-lg p-3">
                    <h3 class="text-2xl font-semibold text-emerald-700">
                        Redaguojama dalis
                    </h3>
                    <h3 class="text-lg text-emerald-700">{{ step.title }}</h3>
                    <form @submit.prevent="submitForm">
                        <div class="mt-4">
                            <InputLabel class="text-lg text-emerald-700">Pavadinimas</InputLabel>
                            <TextInput v-model="form.title"
                                class="w-full px-3 py-2 border border-gray-300 text-black rounded-lg" type="text" />
                            <InputError class="mt-2" :message="form.errors.title ? 'Dalies pavadinimas būtinas.' : ''" />
                        </div>
                        <div class="mt-4">
                            <InputLabel class="text-lg text-emerald-700">Aprašymas</InputLabel>
                            <textarea v-model="form.description"
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                rows="5"></textarea>

                            <InputError class="mt-2"
                                :message="form.errors.description ? 'Dalies aprašymas būtinas.' : ''" />
                        </div>
                        <div class="mt-4">
                            <InputLabel class="text-lg text-emerald-700">YouTube vaizdo įrašo ID</InputLabel>
                            <TextInput v-model="form.youtube_video_id"
                                class="w-full px-3 py-2 border border-gray-300 text-black rounded-lg" type="text" />
                            <InputError class="mt-2"
                                :message="form.errors.youtube_video_id ? 'Dalies YouTube vaizdo įrašo ID būtinas.' : ''" />
                        </div>
                        <div class="mt-6 flex justify-between">
                            <button type="submit" class="text-emerald-700 font-semibold text-md">
                                Atnaujinti
                            </button>
                            <button class="text-black font-semibold text-md" @click="closeModal">
                                Atšaukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
    </teleport>
</template>
