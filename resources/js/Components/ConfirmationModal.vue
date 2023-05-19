<script setup>
import { computed } from 'vue';

const props = defineProps({
    open: Boolean,
    title: String,
    message: String,
    confirmText: String,
    cancelText: String,
    maxWidth: {
        type: String,
        default: 'md',
    },
});

const emit = defineEmits(['confirm', 'cancel']);

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0"
                    enter-to-class="opacity-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
                    <div v-show="open" class="fixed inset-0 transform transition-all" @click="cancel">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </transition>

                <transition enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div v-show="open"
                        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass">
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-emerald-700">{{ title }}</h2>
                            <p class="mt-4 text-gray-600">{{ message }}</p>
                        </div>
                        <div class="flex justify-end p-6 space-x-4">
                            <button class="bg-emerald-700 text-white px-4 py-2 rounded-md" @click="confirm">
                                {{ confirmText }}
                            </button>
                            <button class="text-emerald-700 px-4 py-2 rounded-md" @click="cancel">
                                {{ cancelText }}
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
