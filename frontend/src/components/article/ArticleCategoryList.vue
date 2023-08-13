<script setup>
import ArticleCategoryItem from '@/components/article/ArticleCategoryItem.vue';
import { defineProps } from 'vue';

defineProps({
    categories: {
        type: Array,
        required: true,
    }
});

</script>

<template>
  <ul class="articleCategory__list">
    <ArticleCategoryItem v-for="category in categories" :key="category.id" :category="category" />
  </ul>
</template>
