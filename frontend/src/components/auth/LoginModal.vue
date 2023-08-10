<template>
  <Modal>
    <Form v-model:is-processing="isProcessing" :handleLogic="loginUser">
      <h2 class="modal__title">Login</h2>

      <Input
        v-model:value="form.email"
        for-key="email"
        label="Email"
        type="email"
        :required="false"
      />
      <Input
        v-model:value="form.password"
        for-key="password"
        label="Password"
        type="password"
        :required="false"
      />
      <Button type="submit" :loading="isProcessing">Login</Button>
    </Form>
  </Modal>
</template>

<script setup>
import Modal from '@/components/general/ModalComponent.vue';
import { ref } from 'vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import User from '@/requests/User.js';
import { toast } from 'vue3-toastify';
import Form from '@/components/general/FormComponent.vue';
import { useUserStore } from '@/stores/user.js';
import { useModalStore } from '@/stores/modal.js';

const userStore = useUserStore();
const modalStore = useModalStore();

const isProcessing = ref(false);
const form = ref({
  email: '',
  password: '',
});

const loginUser = async () => {
  const response = await User.login(form.value);
  localStorage.setItem('token', response.data.token);
  await userStore.loginUser();
  modalStore.closeModal();
  toast('Successfully logged in!');
};
</script>
