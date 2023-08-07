<script setup>
import { computed, onMounted, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import Article from '@/requests/Article.js';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

const user = computed(() => userStore.user);

const myArticles = ref([]);

onMounted(() => {
  Article.index({
    author_ids: [user.value.id],
  }).then((myArticlesResponse) => {
    myArticles.value = myArticlesResponse.data.data;
  });
});
</script>
<template>
  <main>
    <ProfileBoard :user="user" />

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
