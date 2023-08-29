<template>
  <div class="article__authorContainer">
    <img
      class="article__authorImage"
      :src="
        author.avatar_url ? author.avatar_url : 'https://picsum.photos/100/100'
      "
      alt=""
    />
    <router-link
      class="article__author-link"
      :to="{ name: 'users.show', params: { id: author.id } }"
    >
      <div class="article__author-content">
        <h4 class="article__authorHeading">By {{ author.name }}</h4>
        <p class="article__authorRole">{{ author.email }}</p>
      </div>
    </router-link>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  author: {
    type: Object,
    required: true,
  },
});
</script>
