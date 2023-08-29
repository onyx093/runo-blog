<template>
  <Modal>
    <Form v-model:is-processing="isProcessing" :handleLogic="resetPassword">
      <h2 class="modal__title">Reset Password</h2>

      <Input
        v-model:value="form.email"
        for-key="email"
        label="Email"
        type="email"
        :required="false"
      />
      <Button type="submit" :loading="isProcessing">Reset Password</Button>
    </Form>
  </Modal>
</template>

<script setup>
import Modal from '@/components/general/ModalComponent.vue';
import { ref } from 'vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import { toast } from 'vue3-toastify';
import Form from '@/components/general/FormComponent.vue';
import { useModalStore } from '@/stores/modal.js';
import Password from '@/requests/Password.js';

const modalStore = useModalStore();

const isProcessing = ref(false);
const form = ref({
  email: '',
});

const resetPassword = async () => {
  const response = await Password.forgotPassword(form.value);
  toast(response.data.message);
  modalStore.closeModal();
};
</script>
