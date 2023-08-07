<script setup>
import { defineProps, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import Form from '@/components/general/FormComponent.vue';
import User from '@/requests/User';
import { toast } from 'vue3-toastify';

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const errors = ref({});
const isProcessing = ref(false);
const form = ref({
  name: props.user.name,
  email: props.user.email,
});

const editProfile = async () => {
  await User.edit(props.user.id, form.value);
  toast('User profile updated!');
};
</script>

<template>
  <main>
    <ProfileBoard :user="props.user" />

    <section class="section">
      <div class="section__inner">
        <h2 class="section__heading">Edit profile</h2>

        <Form
          v-model:errors="errors"
          v-model:is-processing="isProcessing"
          :handleLogic="editProfile"
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
          <Button type="submit" :loading="isProcessing">Edit profile</Button>
        </Form>
      </div>
    </section>
  </main>
</template>
