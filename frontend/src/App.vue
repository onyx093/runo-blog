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
    <footer class="main-footer">
        <div class="main-footer__upper">
            <div class="main-footer__upper-inner">
                <div class="main-footer__upper-col">
                    <h5 class="main-footer__upper-title">Contact the publisher</h5>
                    <p class="main-footer__upper-content">mike@runo.com</p>
                    <p class="main-footer__upper-content">+944 450 904 505</p>
                </div>
                <div class="main-footer__upper-col">
                    <h5 class="main-footer__upper-title">Explore</h5>
                    <p class="main-footer__upper-content">About</p>
                    <p class="main-footer__upper-content">Partners</p>
                    <p class="main-footer__upper-content">Job Opportunities</p>
                    <p class="main-footer__upper-content">Advertise</p>
                    <p class="main-footer__upper-content">Membership</p>
                </div>
                <div class="main-footer__upper-col">
                    <h5 class="main-footer__upper-title">Contact the publisher</h5>
                    <p class="main-footer__upper-content">191 Middleville Road,</p>
                    <p class="main-footer__upper-content">NY 1001, Sydney,</p>
                    <p class="main-footer__upper-content">Australia</p>
                </div>
                <div class="main-footer__upper-col">
                    <h5 class="main-footer__upper-title">Contact the publisher</h5>
                    <p class="main-footer__upper-content">191 Middleville Road,</p>
                    <p class="main-footer__upper-content">NY 1001, Sydney,</p>
                    <p class="main-footer__upper-content">Australia</p>
                </div>
            </div>
        </div>
        <div class="main-footer__bottom">
            <div class="main-footer__bottom-inner">
                <div class="main-footer__bottom-col">
                    <p class="main-footer__bottom-text">2021 | RUNO Publisher Studio</p>
                </div>
                <div class="main-footer__bottom-col">
                    <p class="main-footer__bottom-text">Subscribe now</p>
                </div>
            </div>
        </div>
    </footer>
</template>

<style lang="scss">
@import '@/assets/sass/style.scss';
</style>
