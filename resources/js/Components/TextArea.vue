<script setup>
import { onMounted, ref } from 'vue';

defineProps({
  modelValue: String,
  rows: {
    type: Number,
    default: 6,
  },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <textarea
      ref="input"
      class="w-full bg-white border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder:capitalize resize-none"
      v-html="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :rows="rows ? rows : 6"
  />
</template>
