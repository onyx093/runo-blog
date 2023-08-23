import Http from './Http';

export default {
  async forgotPassword(params) {
    return await Http.post(`/forgot-password`, params);
  },
  async resetPassword() {
    return await Http.get(`/reset-password`);
  },
  async newPassword(params) {
    return await Http.post(`/reset-password`, params);
  },
};
