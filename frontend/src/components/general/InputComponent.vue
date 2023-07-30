<template>
    <div class="input__group">
        <label :for="inputKey" class="input__label">{{ label }}</label>
        <input :type="type" :name="key" class="input" />
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    inputKey: String,
    label: String,
    type: String,
});
</script>
