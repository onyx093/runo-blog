<script setup>
import { defineProps } from 'vue';

defineProps({
  user: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <div class="profileBoard">
    <div class="profileBoard__inner">
      <div class="profilePhoto">
        <img class="profilePhoto__img" src="/images/main_bg.jpg" alt="" />
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
