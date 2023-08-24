<script setup>
import { computed, ref, watch } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import { useUserStore } from '@/stores/user';
import { useErrorStore } from '@/stores/error';
import handleError from '@/utils/handleError.js';
import { useRoute } from 'vue-router';
import User from '@/requests/User.js';
import ButtonComponent from '@/components/general/ButtonComponent.vue';
import { toast } from 'vue3-toastify';

const userStore = useUserStore();

const errorStore = useErrorStore();

const route = useRoute();

const user = ref(null);

const myArticles = ref([]);

const visibleArticles = ref(true);
const visibleFollowers = ref(false);
const visibleFollows = ref(false);

const followers = ref([]);
const follows = ref([]);

const nFollowers = computed(() => {
  return followers.value.length;
});

const nFollows = computed(() => {
  return follows.value.length;
});

const isFollowing = ref(false);

watch(
  () => followers.value,
  () => {
    followers.value.forEach((follower) => {
      if (follower.id === userStore.user.id) {
        isFollowing.value = true;
      }
    });
  },
  { deep: true }
);

try {
  const response = await User.show(route.params.id);
  user.value = response.data;
  myArticles.value = user.value.articles;
  followers.value = user.value.followers;
  follows.value = user.value.follows;
  console.log(followers.value);
} catch (error) {
  handleError(error);
}

const handleClick = async () => {
  isLoading.value = true;
  if (isFollowing.value) {
    try {
      const response = await User.unfollow(route.params.id);
      isFollowing.value = false;
      const followerIndex = followers.value.findIndex(
        (follower) => follower.id == userStore.user.id
      );
      if (followerIndex > -1) {
        followers.value.splice(followerIndex, 1);
      }
      toast(response.data.message);
    } catch (error) {
      handleError(error, errorStore);
    } finally {
      isLoading.value = false;
    }
  } else {
    try {
      const response = await User.follow(route.params.id);
      followers.value.push(userStore.user);
      isFollowing.value = true;
      toast(response.data.message);
    } catch (error) {
      handleError(error, errorStore);
    } finally {
      isLoading.value = false;
    }
  }
};

const showArticles = () => {
  visibleArticles.value = !visibleArticles.value;
  visibleFollowers.value = false;
  visibleFollows.value = false;
};

const showFollowing = () => {
  visibleArticles.value = false;
  visibleFollowers.value = false;
  visibleFollows.value = !visibleFollows.value;
};
const showFollowers = () => {
  visibleArticles.value = false;
  visibleFollows.value = false;
  visibleFollowers.value = !visibleFollowers.value;
};

const isLoading = ref(false);
</script>
<template>
  <main>
    <ProfileBoard :user="user" :owner="false" :showProfileInfo="true">
      <div class="section__button-wrapper">
        <ButtonComponent
          :loading="isLoading"
          v-if="userStore.isLoggedIn"
          class="section__button-follow"
          @btn-click="handleClick()"
          >{{ isFollowing ? 'Unfollow' : 'Follow' }}</ButtonComponent
        >
      </div>
    </ProfileBoard>

    <section class="section">
      <div class="section__info">
        <div class="section__info-item" @click="showArticles">
          {{ user.articles.length + ' articles' }}
        </div>
        <div class="section__info-item" @click="showFollowers">
          {{ nFollowers }}
          <span> followers</span>
        </div>
        <div class="section__info-item" @click="showFollowing">
          {{ nFollows }}
          <span> following</span>
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
            />
          </div>
        </div>
        <div v-if="visibleFollowers" class="section__inner-followers">
          <h2 class="section__heading">Followers</h2>
          <ul class="section__followers-container">
            <router-link
              :to="{ name: 'users.show', params: { id: follower.id } }"
              class="section__followers-item"
              v-for="follower in followers"
              :key="follower.id"
            >
              <img
                v-if="follower.avatar_url"
                class="section__followers-item--img"
                :src="follower.avatar_url"
                alt=""
              />
              <img
                v-else
                class="section__followers-item--img"
                src="https://picsum.photos/100/100"
                alt=""
              />
              <span class="section__followers-item--name">{{
                follower.name
              }}</span>
            </router-link>
          </ul>
        </div>
        <div v-if="visibleFollows" class="section__inner-followers">
          <h2 class="section__heading">Following</h2>
          <ul class="section__followers-container">
            <router-link
              :to="{ name: 'users.show', params: { id: follow.id } }"
              class="section__followers-item"
              v-for="follow in follows"
              :key="follow.id"
            >
              <img
                v-if="follow.avatar_url"
                class="section__followers-item--img"
                :src="follow.avatar_url"
                alt=""
              />
              <img
                v-else
                class="section__followers-item--img"
                src="https://picsum.photos/100/100"
                alt=""
              />
              <span class="section__followers-item--name">{{
                follow.name
              }}</span>
            </router-link>
          </ul>
        </div>
      </div>
    </section>
  </main>
</template>
