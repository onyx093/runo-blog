<template>
    <article v-if="article">
        <section class="mainArticle">
            <div class="mainArticle__bg">
                <img src="/images/show_article_bg.jpg" alt="">
            </div>
            <div class="mainArticle__inner mainArticle__inner--articleShow">
                <ArticleCategory />
                <h2 class="mainArticle__heading">{{ article.title }}</h2>
                <p class="mainArticle__content">
                    <span class="mainArticle__contentText">{{ article.content }}</span>
                </p>
                <p class="mainArticle__author">{{ article.author.name }}</p>
            </div>
        </section>
        <section class="articleContent">
            <div class="articleContent__inner">
                <div class="articleContent__text">
                    {{ article.content }}
                </div>
                <ArticleCategory class="articleCategory__list--articleShow" />

                <footer class="articleContent__footer">
                    <ArticleAuthor :author="article.author" />
                </footer>

                <CommentList :comments="article.comments" />
            </div>
        </section>
    </article>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import ArticleCategory from '@/components/article/ArticleCategory.vue';
import ArticleAuthor from '@/components/article/ArticleAuthor.vue';
import CommentList from '@/components/comment/CommentList.vue'
import Article from '../requests/Article';

const route = useRoute();
const article = ref(null);

onMounted(() => {
    Article.show(route.params.id).then( response => {
        article.value = response.data;
    });
});

</script>
