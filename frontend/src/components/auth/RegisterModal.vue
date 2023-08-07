<template>
  <Modal @close="emits('update:registerModalOpened', false)">
    <Form
      v-model:errors="errors"
      v-model:is-processing="isProcessing"
      :handleLogic="registerUser"
    >
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
  registerModalOpened: Boolean,
});

const emits = defineEmits(['update:registerModalOpened', 'userGotToken']);

const errors = ref({});
const isProcessing = ref(false);
const form = ref({
  name: '',
  email: '',
  password: '',
});

const registerUser = async () => {
  const response = await User.register(form.value);
  localStorage.setItem('token', response.data.token);
  emits('update:registerModalOpened', false);
  emits('userGotToken');
  toast('Successfully registered!');
};
</script>
