<template>
    <div class="article">
        <div class="article__image-container">
            <ArticleCategory class="article-category__list--articleCard" />
            <router-link :to="{name: 'article.show'}">
                <img class="article__image" :src="getArticleImage(props.imgWidth, props.imgHeight)" alt="">
            </router-link>
        </div>
        <div class="article__content" :class="{'article__content--bottom-border-hidden article__content--floating': floatingText}">
            <span class="article__date" :class="{'article__date--contrast': floatingText}">08.08.2021</span>
            <router-link class="article__heading--link" :to="{name: 'article.show'}">
                <h3 class="article__heading" :class="{'article__heading--contrast article__heading--fixed-width': floatingText}">Dream destinations to visit this year in Paris</h3>
            </router-link>
            <p class="article__text" :class="{'article__text--contrast article__text--fixed-width': floatingText}">Progressively incentivize cooperative systems through technically sound functionalities. The credibly productive seamless data.</p>
        </div>
        <ArticleAuthor />
    </div>
</template>

<script setup>
import ArticleCategory from '@/components/article/ArticleCategory.vue';
import ArticleAuthor from '@/components/article/ArticleAuthor.vue';
import { defineProps } from 'vue';

const props = defineProps({
    floatingText: Boolean,
    imgWidth: Number,
    imgHeight: Number,
});

const getArticleImage = (imgW, imgH) => `https://picsum.photos/${imgW}/${imgH}`;

</script>
