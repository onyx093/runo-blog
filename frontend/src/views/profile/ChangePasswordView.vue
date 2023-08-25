<script setup>
import { computed, ref } from 'vue';
import ProfileBoard from '@/components/profile/ProfileBoard.vue';
import Input from '@/components/general/InputComponent.vue';
import Button from '@/components/general/ButtonComponent.vue';
import Form from '@/components/general/FormComponent.vue';
import User from '@/requests/User';
import { toast } from 'vue3-toastify';
import { useUserStore } from '@/stores/user';
import handleError from '@/utils/handleError.js';
import { useErrorStore } from '@/stores/error.js';

const userStore = useUserStore();
const errorStore = useErrorStore();

const user = computed(() => userStore.user);

const isProcessing = ref(false);
const form = ref({
  oldPassword: '',
  newPassword: '',
  newPassword_confirmation: '',
});

const changePassword = async () => {
  try {
    const response = await User.changePassword(user.value.id, form.value);
    userStore.setUser(response.data);
    toast.success('User password changed!');
    form.value.oldPassword = '';
    form.value.newPassword = '';
    form.value.newPassword_confirmation = '';
  } catch (error) {
    console.log(error);
    handleError(error, errorStore);
  }
};
</script>

<template>
  <main>
    <ProfileBoard :user="user" :show-profile-info="true">
      <template #nav_links>
        <RouterLink class="profileLink" :to="{ name: 'profile.index' }"
          >Back to profile</RouterLink
        >
        <RouterLink class="profileLink" :to="{ name: 'profile.edit' }"
          >Manage subscription</RouterLink
        >
      </template>
    </ProfileBoard>

    <div class="editProfileForm">
      <div class="editProfileForm__inner">
        <section class="section">
          <div class="section__inner">
            <h2 class="section__heading section__heading--centered">
              Change password
            </h2>

            <Form
              v-model:is-processing="isProcessing"
              :handle-logic="changePassword"
            >
              <Input
                v-model:value="form.oldPassword"
                for-key="oldPassword"
                label="Old Password"
                type="password"
                :required="false"
              />
              <Input
                v-model:value="form.newPassword"
                for-key="newPassword"
                label="New Password"
                type="password"
                :required="false"
              />
              <Input
                v-model:value="form.newPassword_confirmation"
                for-key="newPassword_confirmation"
                label="Confirm Password"
                type="password"
                :required="false"
              />
              <Button type="submit" :loading="isProcessing">Change password</Button>
            </Form>
          </div>
        </section>
      </div>
    </div>
  </main>
</template>
