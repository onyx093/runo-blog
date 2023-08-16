<script setup>
import Tag from '@/requests/Tag.js';
import { ref } from 'vue';
import handleError from '@/utils/handleError.js';
import CategoryItem from '@/components/category/CategoryItem.vue';

const categories = ref([]);

try {
  const response = await Tag.index();
  categories.value = response.data.data;
} catch (error) {
  handleError(error);
}
</script>

<template>
  <h2 class="section__heading">Popular topics</h2>
  <ul class="section__categories">
    <li class="section__category">
      <a href="#" class="section__categoryLink section__categoryLink--active"
        >All</a
      >
    </li>
    <CategoryItem
      v-for="category in categories"
      :key="category.id"
      :category="category"
    />
    <li class="section__category section__category--last">
      <a href="#" class="section__categoryLink">View All</a>
    </li>
  </ul>
</template>
