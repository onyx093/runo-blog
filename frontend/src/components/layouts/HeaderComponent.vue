<template>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__title">LevelUp blog</h1>
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item">
                        <router-link class="header__nav-item-link" :to="{name: 'home'}">Home</router-link>
                    </li>
                    <li class="header__nav-item">
                        <router-link class="header__nav-item-link" to="#">About</router-link>
                    </li>
                    <li class="header__nav-item">
                        <router-link class="header__nav-item-link" :to="{name: 'article.show'}">Articles</router-link>
                    </li>
                    <li class="header__nav-item">
                        <router-link class="header__nav-item-link" to="#">Sign in</router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>
