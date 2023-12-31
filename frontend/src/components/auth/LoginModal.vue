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
      <div class="modal__button-wrapper">
        <Button type="submit" :loading="isProcessing">Login</Button>
        <span
          v-if="count > 0"
          class="modal__button--password"
          :loading="isProcessing"
          @click="handleReset"
          >Forgot Password?</span
        >
      </div>
    </Form>
    <div class="modal__oauth">
      <span class="modal__oauth-txt">or login with</span>
      <div class="modal__oauth-providers">
        <img
          class="modal__oauth-github"
          src="/images/github_logo.png"
          alt=""
          @click="loginGithub"
        />
      </div>
    </div>
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
import { useNotificationStore } from '../../stores/notification';

const userStore = useUserStore();
const modalStore = useModalStore();
const notificationStore = useNotificationStore();

const isProcessing = ref(false);
const form = ref({
  email: '',
  password: '',
});

const count = ref(0);

const loginUser = async () => {
  count.value += 1;
  const response = await User.login(form.value);
  localStorage.setItem('token', response.data.token);
  await userStore.loginUser();
  await notificationStore.getNotifications();
  modalStore.closeModal();
  toast('Successfully logged in!');
};

const handleReset = () => {
  modalStore.closeModal();
  modalStore.openModal('resetPassword');
};

// Presentation only
const loginGithub = async () => {
  try {
    const response = await User.githubAuth();
    localStorage.setItem('token', response.data.token);
    await userStore.loginUser();
    modalStore.closeModal();
    toast('Successfully logged in!');
  } catch (error) {
    console.log(error);
  }
};
</script>
