<template>
  <Modal @close="emits('update:loginModalOpened', false)">
    <h2 class="modal__title">Login</h2>

    <Input for-key="email" label="Email" type="email" />
    <Input for-key="password" label="Password" type="password" />
    <Button type="submit">Login</Button>
  </Modal>
</template>

<script setup>
import Modal from '@/components/general/ModalComponent.vue';
import { defineEmits, defineProps } from 'vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';

defineProps({
  loginModalOpened: Boolean,
});

const emits = defineEmits(['update:loginModalOpened']);
</script>
