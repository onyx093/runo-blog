<script setup>
import { RouterView, useRoute } from 'vue-router';
import Header from '@/components/layouts/HeaderComponent.vue';
import Footer from '@/components/layouts/FooterComponent.vue';
import Loader from '@/components/layouts/LoaderComponent.vue';
import LoginModal from '@/components/auth/LoginModal.vue';
import RegisterModal from '@/components/auth/RegisterModal.vue';
import { useModalStore } from '@/stores/modal.js';
import { useUxStore } from '@/stores/ux.js';

const modalStore = useModalStore();
const uxStore = useUxStore();

const route = useRoute();
</script>

<template>
  <Header />
  <RouterView v-slot="{ Component }">
    <template v-if="Component">
      <Transition mode="out-in">
        <KeepAlive>
          <Suspense>
            <!-- main content -->
            <component :is="Component" :key="route.fullPath"></component>

            <!-- loading state -->
            <template #fallback>
              <Loader />
            </template>
          </Suspense>
        </KeepAlive>
      </Transition>
    </template>
  </RouterView>
  <LoginModal v-if="modalStore.modal === 'login'" />
  <RegisterModal v-if="modalStore.modal === 'register'" />
  <Loader v-if="uxStore.isLoading" />
  <Footer />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
