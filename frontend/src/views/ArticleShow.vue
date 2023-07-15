<template>
    <article>
        <section class="main-article">
            <div class="main-article__bg">
                <img src="/images/show_article_bg.jpg" alt="">
            </div>
            <div class="main-article__inner main-article__inner--article-show">
                <ArticleCategory />
                <h2 class="main-article__heading">Richard Norton photorealistic rendering as real photos</h2>
                <p class="main-article__content">
                    <span class="main-article__content-text">Progressively incentivize cooperative systems through technically sound functionalities.<br />The credibly productive seamless data.</span>
                </p>
                <p class="main-article__author">By Jennifer Lawrence</p>
            </div>
        </section>
        <section class="article-content">
            <div class="article-content__inner">
                <div class="article-content__text">
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vitae nihil hic, incidunt vero earum odit consequatur excepturi voluptatem culpa dignissimos, accusantium rem molestiae id! Aspernatur reiciendis consequuntur id a amet suscipit vero, perferendis quia accusamus commodi cumque dolores sint repellat. Et commodi placeat praesentium earum voluptate ea nesciunt aliquam? Quae?</p>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vitae nihil hic, incidunt vero earum odit consequatur excepturi voluptatem culpa dignissimos, accusantium rem molestiae id! Aspernatur reiciendis consequuntur id a amet suscipit vero, perferendis quia accusamus commodi cumque dolores sint repellat. Et commodi placeat praesentium earum voluptate ea nesciunt aliquam? Quae?</p>
                    <blockquote>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique eaque cum in tenetur dolorum incidunt asperiores dignissimos maxime quidem numquam?</blockquote>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vitae nihil hic, incidunt vero earum odit consequatur excepturi voluptatem culpa dignissimos, accusantium rem molestiae id! Aspernatur reiciendis consequuntur id a amet suscipit vero, perferendis quia accusamus commodi cumque dolores sint repellat. Et commodi placeat praesentium earum voluptate ea nesciunt aliquam? Quae?</p>
                </div>
                <ArticleCategory class="article-category__list--article-show" />

                <footer class="article-content__footer">
                    <ArticleAuthor />
                </footer>
            </div>
        </section>
    </article>
</template>

<script setup>
import ArticleCategory from '@/components/article/ArticleCategory.vue';
import ArticleAuthor from '@/components/article/ArticleAuthor.vue';
</script>

<style lang="scss" scoped>

</style>
