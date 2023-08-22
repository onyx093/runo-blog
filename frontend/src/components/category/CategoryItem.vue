<script setup>
import { defineProps, inject } from 'vue';

defineProps({
  category: {
    type: Object,
    required: true,
  },
});

const selectedTag = inject('selectedTag');

const getSelectedTag = (tagName) => {
  selectedTag(tagName);
};
</script>

<template>
  <li class="section__category">
    <a
      href="#"
      class="section__categoryLink"
      @click.prevent="getSelectedTag(category.name)"
      >{{ category.name }}</a
    >
  </li>
</template>
