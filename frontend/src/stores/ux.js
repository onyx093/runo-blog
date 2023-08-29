import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useUxStore = defineStore('ux', () => {
  const isLoading = ref(false);

  function setIsLoading(value) {
    isLoading.value = value;
  }

  return { isLoading, setIsLoading };
});
