<script setup>
import { RouterView, useRoute } from 'vue-router';
import Header from '@/components/layouts/HeaderComponent.vue';
import Footer from '@/components/layouts/FooterComponent.vue';
import LoginModal from '@/components/auth/LoginModal.vue';
import RegisterModal from '@/components/auth/RegisterModal.vue';
import { useModalStore } from '@/stores/modal.js';

const modalStore = useModalStore();

const route = useRoute();
</script>

<template>
  <Header />
  <RouterView :key="route.fullPath" />
  <LoginModal v-if="modalStore.modal === 'login'" />
  <RegisterModal v-if="modalStore.modal === 'register'" />
  <Footer />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
