<template>
  <div class="input__group">
    <label :for="forKey" class="input__label">{{ label }}</label>
    <textarea
      v-if="type === 'textarea'"
      :name="forKey"
      v-bind="$attrs"
      :class="{ 'input--error': errorStore.getError(forKey) }"
      :required="required"
      @input="onInputChange(forKey, $event)"
    ></textarea>
    <input
      v-else
      :type="type"
      :name="forKey"
      class="input"
      :class="{ 'input--error': errorStore.getError(forKey) }"
      :required="required"
      :value="value"
      :readonly="readonly"
      @input="onInputChange(forKey, $event)"
    />
    <span v-if="errorStore.getError(forKey)" class="input__errorText">{{
      errorStore.getError(forKey)
    }}</span>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
};
</script>

<script setup>
import { useErrorStore } from '@/stores/error.js';

const errorStore = useErrorStore();

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
  value: {
    type: String,
    required: true,
  },
  required: {
    type: Boolean,
    default: false,
  },
  readonly: {
    type: Boolean,
    default: false,
  },
});

const onInputChange = (forKey, event) => {
  emits('update:value', event.target.value);
  errorStore.deleteError(forKey);
};
</script>
