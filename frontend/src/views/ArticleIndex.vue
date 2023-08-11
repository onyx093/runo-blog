<template>
  <main>
    <section class="section">
      <div class="section__inner">
        <HomeCategories />

        <div class="articles">
          <ArticleCard
            v-for="article in articles"
            :key="article.id"
            :article="article"
            :imgWidth="310"
            :imgHeight="280"
          />
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import ArticleCard from '@/components/article/ArticleCard.vue';
import HomeCategories from '@/components/homepage/HomeCategories.vue';
import Article from '@/requests/Article.js';
import handleError from '@/utils/handleError.js';

const articles = ref([]);

try {
  const response = await Article.index();
  articles.value = response.data.data;
} catch (error) {
  handleError(error);
}
</script>
