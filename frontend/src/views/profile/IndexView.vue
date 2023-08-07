<script setup>
import { defineProps, onMounted, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import Article from '@/requests/Article.js';

const myArticles = ref([]);

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

onMounted(() => {
  Article.index({
    author_ids: [props.user.id],
  }).then((myArticlesResponse) => {
    myArticles.value = myArticlesResponse.data.data;
  });
});
</script>
<template>
  <main>
    <ProfileBoard :user="props.user" />

    <section class="section">
      <div class="section__inner">
        <h2 class="section__heading">My Articles</h2>

        <div class="articles articles--related">
          <RelatedArticleCard
            v-for="article in myArticles"
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
