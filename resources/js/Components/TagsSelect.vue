<script setup>
const props = defineProps({
    tags: Array,
});

const emit = defineEmits(['update:modelValue']);

const tagOptions = props.tags.map(tag => ({ value: tag.id, label: tag.name }));
const selectedTags = ref([]);

function onChange(value) {
    if (value.length <= 3) {
        selectedTags.value = value;
        emit('update:modelValue', value);
    }
}
</script>

<template>
    <SelectInput v-model="selectedTags" :options="tagOptions" placeholder="Choose tags" @update:modelValue="onChange"
        multiple />
</template>
  