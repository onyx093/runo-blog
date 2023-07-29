<template>
  <main>
    <section class="section">
        <div class="section__inner">
            <HomeCategories />

            <div class="articles">
                <ArticleCard v-for="article in articles" :key="article.id" :article="article" :imgWidth="310" :imgHeight="280" />
            </div>

        </div>
    </section>
  </main>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import ArticleCard from '@/components/article/ArticleCard.vue';
import HomeCategories from '@/components/homepage/HomeCategories.vue'
import Article from '@/requests/Article.js';

const articles = ref([]);

onMounted( () => {
  Article.index().then( response => {
      articles.value = response.data;
  });

});
</script>

