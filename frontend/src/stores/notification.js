import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import User from '@/requests/User.js';

export const useNotificationStore = defineStore('notification', () => {
  const userNotifications = ref([]);

  const numberOfNotifications = computed(() => {
    return userNotifications.value.length;
  });

  async function getNotifications() {
    try {
      const response = await User.getNotifications();
      response.data.forEach((notification) => {
        userNotifications.value.push(notification.data);
      });
      //   console.log(response.data);
    } catch (error) {
      userNotifications.value = undefined;
    }
  }

  function clearNotifications() {
    userNotifications.value = [];
  }

  return {
    userNotifications,
    numberOfNotifications,
    getNotifications,
    clearNotifications,
  };
});
