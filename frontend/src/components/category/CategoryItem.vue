<script setup>
import { defineProps } from 'vue';
defineProps({
    category: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <li class="section__category">
      <a href="#" class="section__categoryLink">{{ category.name }}</a>
    </li>
</template>
