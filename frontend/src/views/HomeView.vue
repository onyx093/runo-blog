<script setup>
import ArticleCard from '@/components/article/ArticleCard.vue';
import ArticleMain from '@/components/article/ArticleMain.vue';
import HomeCategories from '@/components/homepage/HomeCategories.vue'
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';

</script>

<template>
  <main>
    <ArticleMain />

    <section class="section">
        <div class="section__inner">
            <HomeCategories />

            <div class="articles">
                <ArticleCard :imgWidth="310" :imgHeight="280" v-for="n in 8" :key="n" />
            </div>

            <h2 class="section__heading">Editor's Pick</h2>

            <div class="articles articles--related">
                <RelatedArticleCard :imgWidth="420" :imgHeight="350" :floatingText="true" v-for="n in 3" :key="n" />
            </div>

        </div>
    </section>
  </main>
</template>
