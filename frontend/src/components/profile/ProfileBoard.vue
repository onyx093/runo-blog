<script setup>
import { defineProps, ref } from 'vue';
import myUpload from 'vue-image-crop-upload';

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const showUploadForm = ref(false);
const profileImage = ref(props.user?.avatar_url);
const uploadUrl = ref(`${import.meta.env.VITE_API_URL}/my/upload-avatar`);
const uploadHeaders = ref({
    Authorization: `Bearer ${localStorage.getItem('token')}`,
});

const cropSuccess = (imgDataUrl) => {
    profileImage.value = imgDataUrl;
}
</script>

<template>
  <div class="profileBoard">
    <div class="profileBoard__inner">
      <div class="profilePhoto">
        <my-upload
        v-model="showUploadForm"
        lang-type="en"
        field="img_avatar"
		:width="300"
		:height="300"
        :headers="uploadHeaders"
		:url="uploadUrl"
		img-format="png"
        @crop-success="cropSuccess"></my-upload>
        <img class="profilePhoto__img" :src="profileImage" alt="" @click="showUploadForm = !showUploadForm" />
      </div>
      <div class="profileInfo">
        <p class="profileInfo__name">{{ user?.name }}</p>
        <p class="profileInfo__email">{{ user?.email }}</p>
        <span class="profileInfo__contentDivider"></span>
      </div>
      <div class="profileLinks">
        <RouterLink class="profileLink" :to="{ name: 'profile.edit' }"
          >Edit profile</RouterLink
        >
        <RouterLink class="profileLink" :to="{ name: 'profile.edit' }"
          >Manage subscription</RouterLink
        >
      </div>
    </div>
  </div>
</template>
