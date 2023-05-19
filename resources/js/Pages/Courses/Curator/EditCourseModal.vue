<script setup>
import { ref, watch, onMounted, onUnmounted, defineProps, defineEmits } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    isOpen: Boolean,
});

const emit = defineEmits(['updated', 'close-modal']);

const isOpen = ref(props.isOpen);

watch(
    () => props.isOpen,
    (value) => {
        isOpen.value = value;
    }
);

const closeModal = () => {
    emit('close-modal');
};

const form = useForm({
    title: props.course.title,
    description: props.course.description,
});

function submitForm() {
    form.put(route('courses.update', props.course.id), {
        onSuccess: () => {
            emit('updated', form);
            closeModal();
        },
    });
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && isOpen.value) {
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
                        Redaguojamas kursas
                    </h3>
                    <h3 class="text-lg text-emerald-700">{{ course.title }}</h3>
                    <form @submit.prevent="submitForm">
                        <div class="mt-4">
                            <InputLabel class="text-lg text-emerald-700">Pavadinimas</InputLabel>
                            <TextInput v-model="form.title"
                                class="w-full px-3 py-2 border border-gray-300 text-black rounded-lg" type="text" />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="mt-4">
                            <InputLabel class="text-lg text-emerald-700">Aprašymas</InputLabel>
                            <textarea v-model="form.description"
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                rows="5"></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
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