<script setup>
import { computed, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import RelatedArticleCard from '@/components/article/RelatedArticleCard.vue';
import Article from '@/requests/Article.js';
import User from '@/requests/User.js';
import { useUserStore } from '@/stores/user';
import handleError from '@/utils/handleError.js';

const userStore = useUserStore();

const user = computed(() => userStore.user);

const myArticles = ref([]);

const visibleArticles = ref(true);
const visibleFollowers = ref(false);

const followers = ref([]);

const nFollowers = computed(() => {
  return followers.value.length;
});

try {
  const response = await Article.index({
    author_ids: [user.value.id],
  });
  myArticles.value = response.data.data;
} catch (error) {
  handleError(error);
}

try {
  const response = await User.following();
  followers.value = response.data;
} catch (error) {
  handleError(error);
}

const showArticles = () => {
  visibleArticles.value = !visibleArticles.value;
  visibleFollowers.value = false;
};

const showFollowing = () => {
  visibleArticles.value = false;
  visibleFollowers.value = !visibleFollowers.value;
};
</script>
<template>
  <main>
    <ProfileBoard :user="user" :showProfileInfo="true">
      <template #nav_links>
        <RouterLink class="profileLink" :to="{ name: 'profile.edit' }"
          >Edit profile</RouterLink
        >
        <RouterLink class="profileLink" :to="{ name: 'profile.edit' }"
          >Manage subscription</RouterLink
        >
      </template>
    </ProfileBoard>

    <section class="section">
      <div class="section__info">
        <div class="section__info-item" @click="showArticles">
          {{ myArticles.length + ' articles' }}
        </div>
        <div class="section__info-item" @click="showFollowing">
          {{ nFollowers }}
          <span> following</span>
        </div>
      </div>
      <div class="section__inner">
        <div v-if="visibleArticles" class="section__inner-articles">
          <h2 class="section__heading">My Articles</h2>
          <div class="articles articles--related">
            <div class="article article--blank">
              <div>
                <RouterLink
                  class="profileLink"
                  :to="{ name: 'articles.create' }"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="67"
                    height="67"
                    viewBox="0 0 67 67"
                    fill="none"
                  >
                    <g clip-path="url(#clip0_2290_308)">
                      <path
                        d="M57.2001 57.2001C70.2666 44.1336 70.2666 22.8664 57.2001 9.79988C44.1336 -3.26663 22.8664 -3.26663 9.79988 9.79988C-3.26663 22.8664 -3.26663 44.1336 9.79988 57.2001C22.8664 70.2666 44.1336 70.2666 57.2001 57.2001ZM12.1644 12.1644C23.9325 0.396369 43.0675 0.396369 54.8356 12.1644C66.6036 23.9325 66.6036 43.0675 54.8356 54.8356C43.0675 66.6036 23.9325 66.6036 12.1644 54.8356C0.396369 43.0675 0.410037 23.9325 12.1644 12.1644Z"
                        fill="#495057"
                      />
                      <path
                        d="M33.5 46.0471C33.9647 46.0471 34.3747 45.8558 34.6891 45.5551C34.9898 45.2544 35.1811 44.8307 35.1811 44.366V35.1675H44.3796C44.8443 35.1675 45.2543 34.9761 45.5687 34.6754C45.8694 34.3747 46.0607 33.951 46.0607 33.4863C46.0607 32.5569 45.309 31.8052 44.3933 31.8189H35.1948V22.6204C35.1948 21.6909 34.443 20.9392 33.5273 20.9529C32.5979 20.9529 31.8461 21.7046 31.8598 22.6204V31.8189H22.6613C21.7319 31.8189 20.9802 32.5706 20.9938 33.4863C20.9938 34.4158 21.7456 35.1675 22.6613 35.1538H31.8598V44.3523C31.8188 45.2954 32.5705 46.0471 33.5 46.0471Z"
                        fill="#495057"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_2290_308">
                        <rect width="67" height="67" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                </RouterLink>
              </div>
              <div>
                <RouterLink
                  class="profileLink"
                  :to="{ name: 'articles.create' }"
                  >Add new article</RouterLink
                >
              </div>
            </div>
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
          <h2 class="section__heading">Following</h2>
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
      </div>
    </section>
  </main>
</template>
