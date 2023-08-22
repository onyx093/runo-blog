<script setup>
import { useUserStore } from '@/stores/user.js';
import { useNotificationStore } from '@/stores/notification.js';
import { ref } from 'vue';

const userStore = useUserStore();
const notificationStore = useNotificationStore();

const isVisible = ref(false);

const toggleNotifications = (e) => {
  isVisible.value = !isVisible.value;
  if (!isVisible.value) {
    notificationStore.clearNotifications();
  }
  // try {
  //   const response = UserService.markNotifications();
  //   console.log(response.data);
  // } catch (error) {}
};
</script>

<template>
  <li v-if="userStore.isLoggedIn" class="header__nav-item">
    <div
      class="header__nav-item-link header__nav-item-link--notifications"
      @click="toggleNotifications"
    >
      <svg
        class="header__nav-item-link--bell"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 448 512"
      >
        <path
          d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"
        />
      </svg>
      <div
        v-if="notificationStore.numberOfNotifications > 0"
        class="header__dropdown--notifications"
      >
        {{ notificationStore.numberOfNotifications }}
      </div>
      <div v-if="isVisible" class="header__dropdown-content">
        <span class="header__dropdown--triangle"></span>
        <div class="header__dropdown-header">
          <span class="header__dropdown--heading">Notifications</span>
        </div>
        <div class="header__dropdown-body">
          <div
            v-for="(notification, index) in notificationStore.userNotifications"
            :key="index"
            class="header__dropdown-body--item"
          >
            <a class="header__dropdown-item">
              <img
                class="header__dropdown-item--img"
                :src="notification.user_avatar"
                alt=""
              />
              <span class="header__dropdown-item--txt">{{
                notification.content
              }}</span>
            </a>
            <span class="header_dropdown-item-divider"></span>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>
