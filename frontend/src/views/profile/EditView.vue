<script setup>
import { computed, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import Form from '@/components/general/FormComponent.vue';
import User from '@/requests/User';
import { toast } from 'vue3-toastify';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

const user = computed(() => userStore.user);

const isProcessing = ref(false);
const form = ref({
  name: user.value.name,
  email: user.value.email,
});

const editProfile = async () => {
  try {
    const response = await User.edit(user.value.id, form.value);
    userStore.setUser(response.data);
  } catch (error) {
    console.log(error);
  }
  toast('User profile updated!');
};
</script>

<template>
  <main>
    <ProfileBoard :user="user" />

    <div class="editProfileForm">
      <div class="editProfileForm__inner">
        <section class="section">
          <div class="section__inner">
            <h2 class="section__heading section__heading--centered">
              Edit profile
            </h2>

            <Form
              v-model:errors="errors"
              v-model:is-processing="isProcessing"
              :handleLogic="editProfile"
            >
              <Input
                v-model:value="form.name"
                for-key="name"
                label="Name"
                type="text"
                :required="true"
              />
              <Input
                v-model:value="form.email"
                for-key="email"
                label="Email"
                type="email"
                :required="true"
                :readonly="true"
              />
              <Button type="submit" :loading="isProcessing">Update</Button>
            </Form>
          </div>
        </section>
      </div>
    </div>
  </main>
</template>
