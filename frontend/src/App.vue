<script setup>
import { RouterView, useRoute } from 'vue-router';
import Header from '@/components/layouts/HeaderComponent.vue';
import Footer from '@/components/layouts/FooterComponent.vue';
import LoginModal from '@/components/auth/LoginModal.vue';
import RegisterModal from '@/components/auth/RegisterModal.vue';
import { ref } from 'vue';
import User from '@/requests/User.js';

const route = useRoute();

const loginModalOpened = ref(false);
const registerModalOpened = ref(false);
const user = ref(undefined);

const logoutUser = async () => {
  await User.logout();
  localStorage.removeItem('token');
  user.value = null;
};

const getLoggedInUser = async () => {
  try {
    const response = await User.my();
    user.value = response.data;
  } catch (error) {
    user.value = null;
  }
};

if (localStorage.getItem('token')) {
  getLoggedInUser();
} else {
  user.value = null;
}
</script>

<template>
  <Header
    v-model:loginModalOpened="loginModalOpened"
    v-model:registerModalOpened="registerModalOpened"
    :user="user"
    @logoutUser="logoutUser"
  />
  <RouterView v-if="user !== undefined" :key="route.fullPath" :user="user" />
  <LoginModal
    v-if="loginModalOpened"
    v-model:loginModalOpened="loginModalOpened"
    @userGotToken="getLoggedInUser"
  />
  <RegisterModal
    v-if="registerModalOpened"
    v-model:registerModalOpened="registerModalOpened"
    @userGotToken="getLoggedInUser"
  />
  <Footer />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
