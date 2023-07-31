<template>
  <div class="input__group">
    <label :for="forKey" class="input__label">{{ label }}</label>
    <textarea
      v-if="type === 'textarea'"
      :name="forKey"
      v-bind="$attrs"
      :class="{ 'input--error': error }"
      :required="required"
      @input="(event) => emits('update:value', event.target.value)"
    ></textarea>
    <input
      v-else
      :type="type"
      :name="forKey"
      class="input"
      :class="{ 'input--error': error }"
      :required="required"
      @input="(event) => emits('update:value', event.target.value)"
    />
    <span class="input__errorText">{{ error }}</span>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
};
</script>

<script setup>
import { defineEmits, defineProps } from 'vue';

const emits = defineEmits(['update:value']);

defineProps({
  forKey: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    required: true,
  },
  error: {
    type: String,
    required: true,
  },
  value: {
    type: String,
    required: true,
  },
  required: {
    type: Boolean,
    default: false,
  },
});
</script>
