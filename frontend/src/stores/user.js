import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import User from '@/requests/User.js';

export const useUserStore = defineStore('user', () => {
  const user = ref(undefined);

  const isLoggedIn = computed(() => user.value !== undefined);
  const isGuest = computed(() => user.value === undefined);

  function setUser(newUser) {
    user.value = newUser;
  }

  async function loginUser() {
    try {
      const response = await User.my();
      user.value = response.data;
    } catch (error) {
      user.value = null;
    }
  }

  async function logoutUser() {
    await User.logout();
    localStorage.removeItem('token');
    user.value = undefined;
  }

  return { user, isLoggedIn, isGuest, setUser, loginUser, logoutUser };
});
