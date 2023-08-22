import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export const useNotificationStore = defineStore('notification', () => {
  const userNotifications = ref([]);

  const numberOfNotifications = computed(() => {
    return userNotifications.value.length;
  });

  async function getNotifications() {
    userNotifications.value = [
      'notification 1',
      'notification 2',
      'notification 3',
      'notification 4',
      'notification 5',
    ];
    console.log(userNotifications.value);
    // try {
    //   const response = await UserService.notifications();
    //   response.data.forEach((notification) => {
    //     userNotifications.value.push(notification.data);
    //   });
    // } catch (error) {
    //   userNotifications.value = undefined;
    // }
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
