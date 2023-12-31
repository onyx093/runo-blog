import Http from './Http';

export default {
  async register(params) {
    return await Http.post(`/register`, params);
  },
  async login(params) {
    return await Http.post(`/authenticate`, params);
  },
  async edit(userId, params) {
    return await Http.put(`/users/${userId}`, params);
  },
  async changePassword(userId, params) {
    return await Http.put(`/users/${userId}/change-password`, params);
  },
  async my() {
    return await Http.get(`/my`);
  },
  async logout() {
    return await Http.get(`/logout`);
  },
  async show(id) {
    return await Http.get(`/users/${id}`);
  },
  async follow(id) {
    return await Http.post(`/users/${id}/follow`);
  },
  async unfollow(id) {
    return await Http.post(`/users/${id}/unfollow`);
  },
  async following() {
    return await Http.get(`/users/my-follows`);
  },
  async followers() {
    return await Http.get(`/users/my-followers`);
  },

  async getNotifications() {
    return await Http.get(`/notifications`);
  },
  async markNotifications() {
    return await Http.post(`/notifications`);
  },
  async github() {
    return await Http.get(`/login/github`);
  },
  async githubAuth() {
    return await Http.get(`/authenticate/github`);
  },
};
