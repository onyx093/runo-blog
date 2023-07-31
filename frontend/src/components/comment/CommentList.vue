<template>
  <div class="commentList">
    <div class="commentList__inner">
      <h3 class="commentList__heading">Comments:</h3>

      <div class="commentList__formWrapper">
        <form class="commentList__form" @submit.prevent="submitComment">
          <textarea
            v-model="commentText"
            class="commentList__input"
            cols="30"
            rows="10"
          ></textarea>
          <div class="commentList__buttonWrapper">
            <button type="submit" class="commentList__button">Submit</button>
          </div>
        </form>
        <p v-if="error">{{ error }}</p>
      </div>

      <div v-if="comments.length" class="commentList__items">
        <CommentItem
          v-for="comment in comments"
          :key="comment.id"
          :comment="comment"
        />
      </div>
      <div v-else class="commentList__items">
        <p class="commentList__noComments">No comments yet.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import CommentItem from '@/components/comment/CommentItem.vue';
import { defineProps, ref } from 'vue';
import * as CommentRequest from '@/requests/Comment';
import { useRoute } from 'vue-router';

const route = useRoute();

const props = defineProps({
  comments: {
    type: Array,
    required: true,
  },
});

const commentText = ref('');
const error = ref('');

const submitComment = async () => {
  try {
    const response = await CommentRequest.default.store({
      content: commentText.value,
      article_id: route.params.id,
    });

    props.comments.unshift(response.data);
    commentText.value = '';
  } catch (errorResponse) {
    error.value = errorResponse.response.data.message;
  }
};
</script>
