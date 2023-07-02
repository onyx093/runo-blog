<script setup>
import { RouterView } from 'vue-router'
</script>

<template>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__title">Levelup blog</h1>
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item"><a class="header__nav-item-link" href="#">Home</a></li>
                    <li class="header__nav-item"><a class="header__nav-item-link" href="#">About</a></li>
                    <li class="header__nav-item"><a class="header__nav-item-link" href="#">Articles</a></li>
                    <li class="header__nav-item"><a class="header__nav-item-link" href="#">Sign in</a></li>
                </ul>
            </nav>
        </div>
    </header>
  <RouterView />
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
