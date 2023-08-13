<script setup>
import { computed, ref } from 'vue';
import ArticleCard from '@/components/article/ArticleCard.vue';
import ArticleMain from '@/components/article/ArticleMain.vue';
import CategoryList from '@/components/category/CategoryList.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import Article from '@/requests/Article.js';
import handleError from '@/utils/handleError.js';
import axios from 'axios';

const articles = ref([]);

const featuredArticle = computed(() => articles.value[0]);
const editorsPickArticles = ref([]);

const request1 = Article.index();
const request2 = Article.index();

try {
  const [response1, response2] = await axios.all([request1, request2]);
  articles.value = response1.data.data;
  editorsPickArticles.value = response2.data.data.slice(0, 3);
} catch (error) {
  handleError(error);
}
</script>

<template>
  <main>
    <ArticleMain v-if="featuredArticle" :article="featuredArticle" />

    <section class="section">
      <div class="section__inner">
        <CategoryList />

        <div class="articles">
          <ArticleCard
            v-for="article in articles"
            :key="article.id"
            :article="article"
            :img-width="310"
            :img-height="280"
          />
        </div>

        <h2 class="section__heading">Editor's Pick</h2>

        <div class="articles articles--related">
          <RelatedArticleCard
            v-for="article in editorsPickArticles"
            :key="article.id"
            :article="article"
            :img-width="420"
            :img-height="350"
            :floating-text="true"
          />
        </div>
      </div>
    </section>
  </main>
</template>
