<script setup>
import { defineProps } from 'vue';

defineProps({
  category: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <li class="articleCategory__item">
    <a href="#" class="articleCategory__itemLink">{{ category.name }}</a>
  </li>
</template>
