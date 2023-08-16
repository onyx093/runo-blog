<script setup>
import { useUserStore } from '@/stores/user';
import handleError from '@/utils/handleError.js';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import InputComponent from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import Form from '@/components/general/FormComponent.vue';
import { toast } from 'vue3-toastify';
import { computed, ref, watch } from 'vue';
import { useErrorStore } from '@/stores/error';
import Article from '@/requests/Article';
import { kebabCase } from 'lodash';
import { useRouter } from 'vue-router';
// import { useTagStore } from '@/stores/tag';
import Tag from '@/requests/Tag.js';
import Multiselect from '../../node_modules/vue-multiselect';

const userStore = useUserStore();
// const tagStore = useTagStore();
const errorStore = useErrorStore();
const router = useRouter();

const user = computed(() => userStore.user);
const tags = ref([]);

try {
  const response = await Tag.index();
  tags.value = response.data.map((tag) => {
    return {
      id: tag.id,
      name: tag.name,
    };
  });
} catch (error) {
  handleError(error, errorStore);
}

const isProcessing = ref(false);
const form = ref({
  title: '',
  slug: '',
  content: '',
  tags: [],
});

const slugText = computed(() => form.value.title);
const selectedTags = ref([]);

watch(slugText, (newSlugText) => {
  form.value.slug = kebabCase(newSlugText);
});

const addNewArticle = async () => {
  if (selectedTags.value.length > 0) {
    form.value.tags = selectedTags.value.map((tag) => tag.name);
  }
  try {
    const response = await Article.store(form.value);
    toast.success('New article created!');
    setTimeout(() => {
      router.push({ name: 'article.show', params: { id: response.data.id } });
    }, 3000);
  } catch (error) {
    handleError(error);
  }
};

const uploadImage = (event) => {
  form.value.cover_photo = event.target.files[0];
};

const formattedDate = computed(() => new Date().toLocaleDateString());
</script>

<template>
  <main>
    <ProfileBoard :user="user">
      <template #profile_header>
        <div class="profileHeader">Article editor</div>
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
              Add content
            </h2>

            <Form
              v-model:is-processing="isProcessing"
              :handleLogic="addNewArticle"
              class="manageArticleForm"
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
                      theme="snow"
                      placeholder="Add content"
                      content-type="html"
                      v-model:content="form.content"
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
                    <label class="input__label" for="cover_photo">Cover</label>
                    <input
                      class="input"
                      type="file"
                      accept="image/*"
                      @change="uploadImage"
                    />
                  </div>
                </div>
              </div>
              <Button type="submit" :loading="isProcessing">Add new</Button>
            </Form>
          </div>
        </section>
      </div>
    </div>
  </main>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
