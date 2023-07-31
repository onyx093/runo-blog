<template>
  <form @submit.prevent="submitForm">
    <slot />
  </form>
</template>

<script setup>
import { defineEmits, defineProps } from 'vue';

const props = defineProps({
  errors: Object,
  isProcessing: Boolean,
  handleLogic: Function,
});

const emits = defineEmits([
  'handleLogic',
  'update:errors',
  'update:isProcessing',
]);

const submitForm = async () => {
  emits('update:errors', {});
  emits('update:isProcessing', true);

  try {
    await props.handleLogic();
  } catch (errorResponse) {
    emits('update:errors', errorResponse.response.data.errors);
  } finally {
    emits('update:isProcessing', false);
  }
};
</script>
