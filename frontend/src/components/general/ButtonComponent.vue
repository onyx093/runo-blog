<template>
  <button
    class="button"
    :type="type"
    :disabled="loading"
    @click="emits('btnClick')"
  >
    <div v-if="loading" class="button__loader"></div>
    <slot />
  </button>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
  type: {
    type: String,
    default: 'button',
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const emits = defineEmits(['btnClick']);
</script>
