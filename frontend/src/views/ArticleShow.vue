<template>
  <article v-if="article">
    <section class="mainArticle">
      <div class="mainArticle__bg">
        <img src="/images/show_article_bg.jpg" alt="" />
      </div>
      <div class="mainArticle__inner mainArticle__inner--articleShow">
        <ArticleCategoryList :categories="article.tags" />
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
        <ArticleCategoryList
          :categories="article.tags"
          class="articleCategory__list--articleShow"
        />

        <footer class="articleContent__footer">
          <ArticleAuthor :author="article.author" />
        </footer>

        <CommentList
          :comments="article.comments"
          @new-comment="newCommentAdded"
        />
      </div>
    </section>
  </article>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import ArticleCategoryList from '@/components/article/ArticleCategoryList.vue';
import ArticleAuthor from '@/components/article/ArticleAuthor.vue';
import CommentList from '@/components/comment/CommentList.vue';
import Article from '@/requests/Article';
import handleError from '@/utils/handleError.js';

const route = useRoute();
const article = ref(null);

try {
  const response = await Article.show(route.params.id);
  article.value = response.data;
} catch (error) {
  handleError(error);
}

const newCommentAdded = (comment) => {
  article.value.comments.unshift(comment);
};
</script>
