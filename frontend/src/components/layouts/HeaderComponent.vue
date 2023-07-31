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
          <li class="header__nav-item">
            <a
              class="header__navItemLink"
              href="#"
              @click.prevent="emits('update:loginModalOpened', true)"
              >Sign in</a
            >
          </li>
          <li class="header__nav-item">
            <a
              class="header__navItemLink"
              href="#"
              @click.prevent="emits('update:registerModalOpened', true)"
              >Register</a
            >
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
  loginModalOpened: Boolean,
  registerModalOpened: Boolean,
});

const emits = defineEmits([
  'update:loginModalOpened',
  'update:registerModalOpened',
]);
</script>
