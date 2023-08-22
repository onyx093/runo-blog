import { ref } from 'vue';
import handleError from '@/utils/handleError.js';
import { defineStore } from 'pinia';
import Tag from '@/requests/Tag.js';

export const useTagStore = defineStore('tag', () => {
  const tags = ref([]);

  async function getTags() {
    try {
      const response = await Tag.index();
      tags.value = response.data;
    } catch (error) {
      handleError(error);
    }
  }

  async function getTag(tagId) {
    return await Tag.show(tagId);
  }

  function onSelectedTag(tagId) {
    return tagId;
  }

  return { tags, getTags, getTag, onSelectedTag };
});
