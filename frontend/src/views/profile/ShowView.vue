<script setup>
import { computed, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import Article from '@/requests/Article.js';
import { useUserStore } from '@/stores/user';
import { useErrorStore } from '@/stores/error';
import handleError from '@/utils/handleError.js';
import { useRoute } from 'vue-router';
import User from '@/requests/User.js';
import ButtonComponent from '@/components/general/ButtonComponent.vue';

const userStore = useUserStore();

const errorStore = useErrorStore();

const route = useRoute();

const user = ref(null);

const myArticles = ref([]);

try {
  const response = await User.show(route.params.id);
  user.value = response.data;
  myArticles.value = user.value.articles;
  console.log(user.value);
} catch (error) {
  handleError(error);
}

const handleClick = () => {
  try {
    const response = User.follow(route.params.id);
    console.log(response.data);
  } catch (error) {
    handleError(error, errorStore);
  }
};
</script>
<template>
  <main>
    <ProfileBoard :user="user" :owner="false" :showProfileInfo="true">
      <ButtonComponent @btn-click="handleClick">Follow</ButtonComponent>
      <ButtonComponent>Unfollow</ButtonComponent>
    </ProfileBoard>

    <section class="section">
      <div class="section__inner">
        <h2 class="section__heading">Articles</h2>
        <div class="articles articles--related">
          <RelatedArticleCard
            v-for="article in myArticles"
            :key="article.id"
            :article="article"
            :img-width="420"
            :img-height="350"
            :floating-text="true"
            :editable="true"
          />
        </div>
      </div>
    </section>
  </main>
</template>
