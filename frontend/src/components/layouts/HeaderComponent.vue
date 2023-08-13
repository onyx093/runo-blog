<template>
  <header class="header">
    <div class="header__inner">
      <h1 class="header__title">LevelUp blog</h1>
      <nav class="header__nav">
        <ul class="header__navList">
          <li class="header__nav-item">
            <router-link class="header__navItemLink" :to="{ name: 'home' }"
              >Home</router-link
            >
          </li>
          <li class="header__nav-item">
            <router-link class="header__navItemLink" to="#">About</router-link>
          </li>
          <li class="header__nav-item">
            <router-link
              class="header__navItemLink"
              :to="{ name: 'articles.index' }"
              >Articles</router-link
            >
          </li>
          <template v-if="userLoginStatus === undefined">
            <li class="header__nav-item">
              <a
                class="header__navItemLink"
                href="#"
                @click.prevent="modalStore.openModal('login')"
                >Sign in</a
              >
            </li>
            <li class="header__nav-item">
              <a
                class="header__navItemLink"
                href="#"
                @click.prevent="modalStore.openModal('register')"
                >Register</a
              >
            </li>
          </template>
          <template v-if="userLoginStatus">
              <li class="header__nav-item">
                <router-link
                  class="header__navItemLink"
                  :to="{ name: 'profile.index' }"
                  >My profile</router-link
                >
              </li>
              <li class="header__nav-item">
                <a
                  class="header__navItemLink"
                  href="#"
                  @click.prevent="userStore.logoutUser()"
                  >Logout</a
                >
              </li>
          </template>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { useUserStore } from '@/stores/user.js';
import { useModalStore } from '@/stores/modal.js';
import { onMounted, ref } from 'vue';

const userLoginStatus = ref(null);
const userStore = useUserStore();
const modalStore = useModalStore();

onMounted(() => {
    userLoginStatus.value = userStore.isLoggedIn ? userStore.isLoggedIn : userStore.isGuest;
});

</script>
