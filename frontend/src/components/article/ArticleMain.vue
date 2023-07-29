<template>
    <article class="mainArticle">
        <div class="mainArticle__bg">
            <img src="/images/main_bg.jpg" alt="">
        </div>
        <div class="mainArticle__inner">
            <ArticleCategory />
            <h2 class="mainArticle__heading">
                <router-link class="mainArticle__headingLink" :to="{name: 'article.show', params: { id: article.id }}">
                    {{ article.title }}
                </router-link>
            </h2>
            <p class="mainArticle__content">
                <time class="mainArticle__content--time">{{ formattedDate }}</time>
                <span class="mainArticle__contentDivider"></span>
                <span class="mainArticle__contentText">{{ article.content }}</span>
            </p>
        </div>
    </article>
</template>

<script setup>
import { computed, defineProps } from 'vue';
import ArticleCategory from '@/components/article/ArticleCategory.vue';
import { format } from 'date-fns';

const props = defineProps({
    article: {
        type: Object,
        required: true,
    }
});

const formattedDate = computed( () => format(new Date(props.article.created_at), 'k:mm'));
</script>
