<template>
    <button class="button" :type="type" @click="emits('click')">
        <slot />
    </button>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
    type: {
        type: String,
        default: 'button',
    }
});

const emits = defineEmits(['click']);
</script>
