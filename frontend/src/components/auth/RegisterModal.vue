<template>
  <Modal @close="emits('update:registerModalOpened', false)">
    <form @submit.prevent="registerUser">
      <h2 class="modal__title">Register</h2>

      <Input
        v-model:value="form.name"
        for-key="name"
        label="Name"
        type="text"
        :error="errors.name ? errors.name[0] : null"
        :required="true"
        @update:value="errors.name = null"
      />
      <Input
        v-model:value="form.email"
        for-key="email"
        label="Email"
        type="email"
        :error="errors.email ? errors.email[0] : null"
        :required="true"
        @update:value="errors.email = null"
      />
      <Input
        v-model:value="form.password"
        for-key="password"
        label="Password"
        type="password"
        :error="errors.password ? errors.password[0] : null"
        :required="true"
        @update:value="errors.password = null"
      />
      <Button type="submit" :loading="isProcessing">Register</Button>
    </form>
  </Modal>
</template>

<script setup>
import Modal from '@/components/general/ModalComponent.vue';
import { defineEmits, defineProps, ref } from 'vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import User from '@/requests/User.js';
import { toast } from 'vue3-toastify';

defineProps({
  registerModalOpened: Boolean,
});

const emits = defineEmits(['update:registerModalOpened']);

const form = ref({
  name: '',
  email: '',
  password: '',
});

const errors = ref({});
const isProcessing = ref(false);

const registerUser = async () => {
  isProcessing.value = true;
  errors.value = {};
  try {
    const response = await User.register(form.value);
    localStorage.setItem('token', response.data.token);
    emits('update:registerModalOpened', false);
    toast('Successfully registered!');
  } catch (errorResponse) {
    errors.value = errorResponse.response.data.errors;
  } finally {
    isProcessing.value = false;
  }
};
</script>
