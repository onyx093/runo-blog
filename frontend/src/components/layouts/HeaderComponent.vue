<template>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__title">LevelUp blog</h1>
            <nav class="header__nav">
                <ul class="header__navList">
                    <li class="header__nav-item">
                        <router-link class="header__navItemLink" :to="{name: 'home'}">Home</router-link>
                    </li>
                    <li class="header__nav-item">
                        <router-link class="header__navItemLink" to="#">About</router-link>
                    </li>
                    <li class="header__nav-item">
                        <router-link class="header__navItemLink" :to="{name: 'articles.index'}">Articles</router-link>
                    </li>
                    <li class="header__nav-item">
                        <router-link class="header__navItemLink" to="#">Sign in</router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>
