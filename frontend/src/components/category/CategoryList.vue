<script setup>
import Tag from '@/requests/Tag.js';
import { ref } from 'vue';
import handleError from '@/utils/handleError.js';
import CategoryItem from '@/components/category/CategoryItem.vue';

const categories = ref([]);

const genericCategory = ref({
  id: 9999,
  name: 'view all',
});

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
    <!-- <li class="section__category">
      <CategoryItem :category="firstCategory" />
      <a
        href="#"
        class="section__categoryLink section__categoryLink--active"
        @click.prevent="getSelectedTag('all')"
        >All</a
      >
    </li> -->
    <CategoryItem
      v-for="category in categories"
      :key="category.id"
      :category="category"
    />
    <CategoryItem :category="genericCategory" class="section__category--last" />
    <!-- <li class="section__category section__category--last">
      <a
        href="#"
        class="section__categoryLink"
        @click.prevent="getSelectedTag('all')"
        >View All</a
      >
    </li> -->
  </ul>
</template>
