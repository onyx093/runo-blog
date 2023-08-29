<script setup>
import CommentItem from '@/components/comment/CommentItem.vue';
import { defineEmits, defineProps, ref } from 'vue';
import * as CommentRequest from '@/requests/Comment';
import { useRoute } from 'vue-router';
import Form from '@/components/general/FormComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import Input from '@/components/general/InputComponent.vue';

const route = useRoute();

defineProps({
  comments: {
    type: Array,
    required: true,
  },
});

const commentText = ref('');
const isProcessing = ref(false);

const emits = defineEmits(['newComment']);

const submitComment = async () => {
  const response = await CommentRequest.default.store({
    content: commentText.value,
    article_id: route.params.id,
  });

  emits('newComment', response.data);
  commentText.value = '';
};
</script>

<template>
  <div class="commentList">
    <div class="commentList__inner">
      <h3 class="commentList__heading">Comments:</h3>

      <div class="commentList__formWrapper">
        <Form
          v-model:is-processing="isProcessing"
          :handleLogic="submitComment"
          class="commentList__form"
        >
          <Input
            v-model:value="commentText"
            class="commentList__input"
            for-key="content"
            label="Comment"
            type="textarea"
            :required="false"
          />
          <div class="commentList__buttonWrapper">
            <Button
              type="submit"
              class="commentList__button"
              :loading="isProcessing"
              >Submit</Button
            >
          </div>
        </Form>
        <!-- <p v-if="error" class="commentList__formError">{{ error }}</p> -->
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
