<script setup>
import { useUserStore } from '@/stores/user';
import handleError from '@/utils/handleError.js';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import InputComponent from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import Form from '@/components/general/FormComponent.vue';
import { toast } from 'vue3-toastify';
import { computed, onMounted, ref, watch } from 'vue';
import { useErrorStore } from '@/stores/error';
import { kebabCase } from 'lodash';
import { useRoute, useRouter } from 'vue-router';
import Tag from '@/requests/Tag.js';
import Article from '@/requests/Article.js';
import Multiselect from '../../node_modules/vue-multiselect';
import axios from 'axios';
import myUpload from 'vue-image-crop-upload';

const userStore = useUserStore();
const errorStore = useErrorStore();
const router = useRouter();
const route = useRoute();

const user = computed(() => userStore.user);
const tags = ref([]);
const showUploadForm = ref(false);

const article = ref({});
const articleCoverImage = ref(article.value.cover_url);

const tagRequest = Tag.index();
const articleRequest = Article.show(route.params.id);

onMounted(async () => {
  try {
    const [tagResponse, articleResponse] = await axios.all([
      tagRequest,
      articleRequest,
    ]);
    tags.value = tagResponse.data.data.map((tag) => {
      return {
        id: tag.id,
        name: tag.name,
      };
    });
    // console.log(articleResponse.data);
    article.value = articleResponse.data;
    form.value.title = article.value.title;
    form.value.content = article.value.content;
  } catch (error) {
    handleError(error, errorStore);
  }
});

const isProcessing = ref(false);
const form = ref({
  title: article.value,
  slug: '',
  content: '',
  tags: [],
});

const slugText = computed(() => form.value.title);
const selectedTags = ref([]);

watch(slugText, (newSlugText) => {
  form.value.slug = kebabCase(newSlugText);
});

const updateArticle = async () => {
  if (selectedTags.value.length > 0) {
    form.value.tags = selectedTags.value.map((tag) => tag.name);
  }
  try {
    const response = await Article.update(route.params.id, form.value);
    toast.success('Article has been updated!');
    setTimeout(() => {
      router.push({ name: 'article.show', params: { id: response.data.id } });
    }, 2000);
  } catch (error) {
    handleError(error);
  }
};

const cropSuccess = (imgDataUrl) => {
  const newImage = new File();
  form.value.cover_photo = imgDataUrl;
  articleCoverImage.value = imgDataUrl;
};

const formattedDate = computed(() => new Date().toLocaleDateString());
</script>

<template>
  <main>
    <ProfileBoard :user="user">
      <template #profile_header>
        <div class="profileHeader">{{ article.title }}</div>
      </template>
      <template #nav_links>
        <RouterLink class="profileLink" :to="{ name: 'profile.index' }"
          >Back to profile</RouterLink
        >
        <RouterLink class="profileLink" :to="{ name: 'profile.edit' }"
          >Manage subscription</RouterLink
        >
      </template>
    </ProfileBoard>

    <div class="manageArticle">
      <div class="manageArticle__inner">
        <section class="formSection">
          <div class="section__inner">
            <h2 class="section__heading section__heading--centered">
              Edit content
            </h2>

            <Form
              v-model:is-processing="isProcessing"
              :handle-logic="updateArticle"
              class="manageArticleForm"
              enctype="multipart/form-data"
            >
              <div class="manageArticleForm__inner">
                <div class="manageArticleForm__formGroup">
                  <InputComponent
                    v-model:value="form.title"
                    for-key="title"
                    label="Title"
                    type="text"
                    :required="false"
                  />
                  <InputComponent
                    v-model:value="form.slug"
                    for-key="slug"
                    label="Slug"
                    type="text"
                    :required="false"
                    :readonly="true"
                  />
                  <div class="input__group">
                    <label class="input__label">Tags</label>
                    <QuillEditor
                      v-model:content="form.content"
                      theme="snow"
                      placeholder="Add content"
                      content-type="delta"
                    />
                  </div>
                </div>
                <div class="manageArticleForm__formGroup">
                  <InputComponent
                    :value="formattedDate"
                    for-key="title"
                    label="Date"
                    type="text"
                    :required="false"
                  />
                  <div class="input__group">
                    <label class="input__label">Tags</label>
                    <Multiselect
                      v-model="selectedTags"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      :max="2"
                      track-by="id"
                      :options="tags"
                      :multiple="true"
                      :taggable="true"
                    ></Multiselect>
                  </div>
                  <div class="input__group">
                    <div class="coverPhoto">
                      <my-upload
                        v-model="showUploadForm"
                        field="img_cover"
                        :width="1200"
                        :height="800"
                        lang-type="en"
                        img-format="png"
                        no-circle
                        no-square
                        @crop-success="cropSuccess"
                      ></my-upload>
                      <img
                        class="coverPhoto__img"
                        :src="articleCoverImage"
                        alt=""
                        @click="showUploadForm = !showUploadForm"
                      />
                    </div>
                    <div class="input__group">
                      <input type="file" name="cover_photo" />
                    </div>
                  </div>
                </div>
              </div>
              <Button type="submit" :loading="isProcessing"
                >Update article</Button
              >
            </Form>
          </div>
        </section>
      </div>
    </div>
  </main>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
