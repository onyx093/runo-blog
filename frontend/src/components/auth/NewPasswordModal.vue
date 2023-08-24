<template>
  <Modal>
    <Form v-model:is-processing="isProcessing" :handle-logic="newPassword">
      <h2 class="modal__title">Reset Password</h2>

      <Input
        v-model:value="form.password"
        for-key="password"
        label="New password"
        type="password"
        :required="true"
      />
      <Input
        v-model:value="form.password_confirmation"
        for-key="password"
        label="Confirm password"
        type="password"
        :required="true"
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
import Password from '@/requests/Password.js';
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps({
  token: {
    type: String,
    default: '',
  },
  email: {
    type: String,
    default: '',
  },
});

const isProcessing = ref(false);

const form = ref({
  email: props.email,
  token: props.token,
  password: '',
  password_confirmation: '',
});

const newPassword = async () => {
  const response = await Password.newPassword(form.value);
  toast(response.data.message);
  router.push({ name:  'home'});
};
</script>
