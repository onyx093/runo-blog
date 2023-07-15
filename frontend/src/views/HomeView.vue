<script setup>
import ArticleCard from '@/components/article/ArticleCard.vue';
import ArticleMain from '@/components/article/ArticleMain.vue';
import HomeCategories from '@/components/homepage/HomeCategories.vue'

</script>

<template>
  <main>
    <ArticleMain />

    <section class="section">
        <div class="section__inner">
            <HomeCategories />

            <div class="articles">
                <ArticleCard v-for="n in 8" :key="n" />
            </div>

            <h2 class="section__heading">Editor's Pick</h2>

            <div class="articles editors-pick">
                <div class="article">
                    <div class="article__image-container">
                        <ul class="article__categories">
                            <li class="article__category"><a href="#" class="article__category-link">Adventure</a></li>
                        </ul>
                        <img src="https://picsum.photos/420/350" alt="" class="article__image">
                    </div>
                    <div class="article__content article__content--bottom-border-hidden article__content--floating">
                        <span class="article__date article__date--contrast">08.08.2021</span>
                        <h3 class="article__heading article__heading--contrast article__heading--fixed-width">Richard Norton photorealistic rendering as real photos</h3>
                        <p class="article__text article__text--contrast article__text--fixed-width">Progressively incentivize cooperative systems through technically sound functionalities. The credibly productive seamless data.</p>
                    </div>
                </div>
                <div class="article">
                    <div class="article__image-container">
                        <ul class="article__categories">
                            <li class="article__category"><a href="#" class="article__category-link">Adventure</a></li>
                        </ul>
                        <img src="https://picsum.photos/420/350" alt="" class="article__image">
                    </div>
                    <div class="article__content article__content--bottom-border-hidden article__content--floating">
                        <span class="article__date article__date--contrast">08.08.2021</span>
                        <h3 class="article__heading article__heading--contrast article__heading--fixed-width">Richard Norton photorealistic rendering as real photos</h3>
                        <p class="article__text article__text--contrast article__text--fixed-width">Progressively incentivize cooperative systems through technically sound functionalities. The credibly productive seamless data.</p>
                    </div>
                </div>
                <div class="article">
                    <div class="article__image-container">
                        <ul class="article__categories">
                            <li class="article__category"><a href="#" class="article__category-link">Adventure</a></li>
                        </ul>
                        <img src="https://picsum.photos/420/350" alt="" class="article__image">
                    </div>
                    <div class="article__content article__content--bottom-border-hidden article__content--floating">
                        <span class="article__date article__date--contrast">08.08.2021</span>
                        <h3 class="article__heading article__heading--contrast article__heading--fixed-width">Richard Norton photorealistic rendering as real photos</h3>
                        <p class="article__text article__text--contrast article__text--fixed-width">Progressively incentivize cooperative systems through technically sound functionalities. The credibly productive seamless data.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>
  </main>
</template>
