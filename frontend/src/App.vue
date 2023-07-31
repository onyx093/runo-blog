<script setup>
import { RouterView } from 'vue-router';
import Header from '@/components/layouts/HeaderComponent.vue';
import Footer from '@/components/layouts/FooterComponent.vue';
import LoginModal from '@/components/auth/LoginModal.vue';
import RegisterModal from '@/components/auth/RegisterModal.vue';
import { ref } from 'vue';

const loginModalOpened = ref(false);
const registerModalOpened = ref(false);
</script>

<template>
  <Header
    v-model:loginModalOpened="loginModalOpened"
    v-model:registerModalOpened="registerModalOpened"
  />
  <RouterView />
  <LoginModal
    v-if="loginModalOpened"
    v-model:loginModalOpened="loginModalOpened"
  />
  <RegisterModal
    v-if="registerModalOpened"
    v-model:registerModalOpened="registerModalOpened"
  />
  <Footer />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
