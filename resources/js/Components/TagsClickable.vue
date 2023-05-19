<script>
export default {
    props: {
        tags: {
            type: Array,
            required: true,
        },
        modelValue: {
            type: Array,
            default: () => [],
        },
    },
    emits: ['update:modelValue'],
    computed: {
        selectedTags() {
            return this.modelValue;
        },
    },
    methods: {
        toggleTag(tagId) {
            if (this.selectedTags.includes(tagId)) {
                this.$emit('update:modelValue', this.selectedTags.filter(id => id !== tagId));
            } else {
                this.$emit('update:modelValue', [...this.selectedTags, tagId]);
            }
        },
    },
};
</script>

<template>
    <div class="">
        <button v-for="tag in tags" :key="tag.id"
            :class="['ml-1', selectedTags.includes(tag.id) ?
                'border-emerald-700 bg-emerald-500' : 'bg-emerald-700 text-white', 'rounded-lg py-1 px-2 cursor-pointer focus:outline-none font-semibold text-xs']"
            @click="toggleTag(tag.id)">
            {{ tag.name }}
        </button>
    </div>
</template>
  

  