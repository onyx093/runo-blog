<template>
  <div class="commentItem">
    <div class="commentItem__imageWrapper">
      <img
        class="commentItem__image"
        src="https://picsum.photos/100/100"
        alt=""
      />
    </div>
    <div class="commentItem__content">
      <h4 v-if="comment.author" class="commentItem__contentHeading">
        {{ comment.author.name }}
      </h4>
      <h4 v-else class="commentItem__contentHeading">Anonymous</h4>
      <span class="commentItem__contentDate">{{ formattedDate }}</span>
      <p class="commentItem__contentText">{{ comment.content }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed, defineProps } from 'vue';
import { format, intlFormatDistance } from 'date-fns';

const props = defineProps({
  comment: {
    type: Object,
    required: true,
  },
});

const formattedDate = computed(() => {
  return `${format(
    new Date(props.comment.created_at),
    'dd.MM.yyyy.'
  )} - ${intlFormatDistance(new Date(props.comment.created_at), new Date())}`;
});
</script>
