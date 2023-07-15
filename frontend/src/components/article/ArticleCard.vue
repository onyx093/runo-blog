<template>
    <div class="article">
        <div class="article__image-container">
            <ArticleCategory class="article-category__list--articleCard" />
            <img class="article__image" src="https://picsum.photos/310/280" alt="">
        </div>
        <div class="article__content">
            <span class="article__date">08.08.2021</span>
            <h3 class="article__heading">Dream destinations to visit this year in Paris</h3>
            <p class="article__text">Progressively incentivize cooperative systems through technically sound functionalities. The credibly productive seamless data.</p>
        </div>
        <ArticleAuthor />
    </div>
</template>

<script setup>
import ArticleCategory from '@/components/article/ArticleCategory.vue';
import ArticleAuthor from '@/components/article/ArticleAuthor.vue';


</script>

<style lang="scss">

</style>
