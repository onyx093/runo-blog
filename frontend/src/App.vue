<script setup>
import { RouterView } from 'vue-router'
import Header from '@/components/layouts/HeaderComponent.vue'
import Footer from '@/components/layouts/FooterComponent.vue'
import LoginModal from './components/auth/LoginModal.vue';
import { ref } from 'vue';

const loginModalOpened = ref(false);
</script>

<template>
    <Header
        v-model:loginModalOpened="loginModalOpened"
    />
    <RouterView />
    <LoginModal
        v-if="loginModalOpened"
        v-model:loginModalOpened="loginModalOpened"
    />
    <Footer />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
