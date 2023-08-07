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
  async my() {
    return await Http.get(`/my`);
  },
  async logout() {
    return await Http.get(`/logout`);
  },
};
