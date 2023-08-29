<template>
  <form @submit.prevent="submitForm">
    <slot />
  </form>
</template>

<script setup>
import { defineEmits, defineProps } from 'vue';
import { useErrorStore } from '@/stores/error.js';
import handleError from '@/utils/handleError.js';

const errorStore = useErrorStore();

const props = defineProps({
  isProcessing: Boolean,
  handleLogic: Function,
});

const emits = defineEmits(['handleLogic', 'update:isProcessing']);

const submitForm = async () => {
  errorStore.setErrors({});
  emits('update:isProcessing', true);

  try {
    await props.handleLogic();
  } catch (errorResponse) {
    handleError(errorResponse, errorStore);
  } finally {
    emits('update:isProcessing', false);
  }
};
</script>
