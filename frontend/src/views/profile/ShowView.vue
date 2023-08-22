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

const visibleArticles = ref(false);
const visibleFollowers = ref(false);

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

const showArticles = () => {
  visibleArticles.value = !visibleArticles.value;
  visibleFollowers.value = false;
};

const showFollowers = () => {
  visibleArticles.value = false;
  visibleFollowers.value = !visibleFollowers.value;
};
</script>
<template>
  <main>
    <ProfileBoard :user="user" :owner="false" :showProfileInfo="true">
      <ButtonComponent @btn-click="handleClick">Follow</ButtonComponent>
      <ButtonComponent>Unfollow</ButtonComponent>
    </ProfileBoard>

    <section class="section">
      <div class="section__info">
        <div class="section__info-item" @click="showArticles">
          {{ user.articles.length + ' articles' }}
        </div>
        <div class="section__info-item" @click="showFollowers">
          {{ user.followers?.length ?? '0 followers' }}
        </div>
      </div>
      <div class="section__inner">
        <div v-if="visibleArticles" class="section__inner-articles">
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
        <div v-if="visibleFollowers" class="section__inner-followers">
          <h2 class="section__heading">Followers</h2>
          <ul>
            <li v-for="follower in user.followers">{{ follower }}</li>
          </ul>
        </div>
      </div>
    </section>
  </main>
</template>
