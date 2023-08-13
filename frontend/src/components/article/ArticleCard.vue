<template>
  <div class="article">
    <div class="article__imageContainer">
      <ArticleCategoryList :categories="article.tags" class="articleCategory__list--articleCard" />
      <router-link :to="{ name: 'article.show', params: { id: article.id } }">
        <img
          class="article__image"
          :src="getArticleImage(props.imgWidth, props.imgHeight)"
          alt=""
        />
      </router-link>
    </div>
    <div
      class="article__content"
      :class="{
        'article__content--bottomBorderHidden article__content--floating':
          floatingText,
      }"
    >
      <span
        class="article__date"
        :class="{ 'article__date--contrast': floatingText }"
        >{{ formattedDate }}</span
      >
      <router-link
        class="article__heading--link"
        :to="{ name: 'article.show', params: { id: article.id } }"
      >
        <h3
          class="article__heading"
          :class="{
            'article__heading--contrast article__heading--fixedWidth':
              floatingText,
          }"
        >
          {{ article.title }}
        </h3>
      </router-link>
      <p
        class="article__text"
        :class="{
          'article__text--contrast article__text--fixedWidth': floatingText,
        }"
      >
        {{ article.content }}
      </p>
    </div>
    <ArticleAuthor :author="article.author" />
  </div>
</template>

<script setup>
import ArticleCategoryList from '@/components/article/ArticleCategoryList.vue';
import ArticleAuthor from '@/components/article/ArticleAuthor.vue';
import { computed, defineProps } from 'vue';
import { format } from 'date-fns';

const props = defineProps({
  article: {
    type: Object,
    required: true,
  },
  floatingText: Boolean,
  imgWidth: {
    type: Number,
    default: 310,
  },
  imgHeight: {
    type: Number,
    default: 280,
  },
});

const getArticleImage = (imgW, imgH) => `https://picsum.photos/${imgW}/${imgH}`;

const formattedDate = computed(() =>
  format(new Date(props.article.created_at), 'dd.MM.yyyy')
);
</script>
