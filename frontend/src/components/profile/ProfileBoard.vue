<script setup>
import { defineProps, ref } from 'vue';
import myUpload from 'vue-image-crop-upload';

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  showProfileInfo: {
    type: Boolean,
    default: false,
  },
  owner: {
    type: Boolean,
    default: true,
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
};
</script>

<template>
  <div class="profileBoard">
    <div class="profileBoard__inner">
      <slot name="profile_header"></slot>
      <template v-if="showProfileInfo === true">
        <div class="profilePhoto">
          <my-upload
            v-if="owner"
            v-model="showUploadForm"
            lang-type="en"
            field="img_avatar"
            :width="300"
            :height="300"
            :headers="uploadHeaders"
            :url="uploadUrl"
            img-format="png"
            @crop-success="cropSuccess"
          ></my-upload>
          <img
            class="profilePhoto__img"
            :src="profileImage"
            alt=""
            @click="showUploadForm = !showUploadForm"
          />
        </div>
        <div class="profileInfo">
          <p class="profileInfo__name">{{ user?.name }}</p>
          <p class="profileInfo__email">{{ user?.email }}</p>
          <span class="profileInfo__contentDivider"></span>
        </div>
      </template>
      <slot name="default"></slot>
      <div class="profileLinks">
        <slot name="nav_links"></slot>
      </div>
    </div>
  </div>
</template>
