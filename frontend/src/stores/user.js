import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import User from '@/requests/User.js';
import handleError from '@/utils/handleError.js';
import { useRouter } from 'vue-router';

export const useUserStore = defineStore('user', () => {
  const user = ref(null);
  const router = useRouter();

  const isLoggedIn = computed(() => user.value !== null);

  function setUser(newUser) {
    user.value = newUser;
  }

  async function loginUser() {
    try {
      const response = await User.my();
      user.value = response.data;
    } catch (error) {
      handleError(error);
      user.value = null;
    }
  }

  async function logoutUser() {
    await User.logout();
    localStorage.removeItem('token');
    user.value = null;
    router.push({ name: 'home' });
  }

  return { user, isLoggedIn, setUser, loginUser, logoutUser };
});
