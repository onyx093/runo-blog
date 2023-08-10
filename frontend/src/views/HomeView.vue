<script setup>
import { computed, onMounted, ref } from 'vue';
import ArticleCard from '@/components/article/ArticleCard.vue';
import ArticleMain from '@/components/article/ArticleMain.vue';
import HomeCategories from '@/components/homepage/HomeCategories.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import Article from '@/requests/Article.js';
import handleError from '@/utils/handleError.js';

const articles = ref([]);

const featuredArticle = computed(() => articles.value[0]);
const editorsPickArticles = ref([]);

onMounted( async () => {
    try {
        const response = await Article.index();
        articles.value = response.data.data;
    } catch (error) {
        handleError(error);
    }

    try {
        const responseEditorsPick = await Article.index();
        editorsPickArticles.value = responseEditorsPick.data.data.slice(0, 3);
    } catch (error) {
        handleError(error);
    }
});
</script>

<template>
  <main>
    <ArticleMain v-if="featuredArticle" :article="featuredArticle" />

    <section class="section">
      <div class="section__inner">
        <HomeCategories />

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
