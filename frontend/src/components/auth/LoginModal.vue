<template>
  <Modal @close="emits('update:loginModalOpened', false)">
    <Form
      v-model:errors="errors"
      v-model:is-processing="isProcessing"
      :handleLogic="loginUser"
    >
      <h2 class="modal__title">Login</h2>

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
      <Button type="submit" :loading="isProcessing">Login</Button>
    </Form>
  </Modal>
</template>

<script setup>
import Modal from '@/components/general/ModalComponent.vue';
import { defineEmits, defineProps, ref } from 'vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import User from '@/requests/User.js';
import { toast } from 'vue3-toastify';
import Form from '@/components/general/FormComponent.vue';

defineProps({
  loginModalOpened: Boolean,
});

const emits = defineEmits(['update:loginModalOpened', 'userGotToken']);

const errors = ref({});
const isProcessing = ref(false);
const form = ref({
  email: '',
  password: '',
});

const loginUser = async () => {
  const response = await User.login(form.value);
  localStorage.setItem('token', response.data.token);
  emits('update:loginModalOpened', false);
  emits('userGotToken');
  toast('Successfully logged in!');
};
</script>
