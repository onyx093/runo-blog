import Http from './Http';

export default {
  async register(params) {
    return await Http.post(`/register`, params);
  },
  async login(params) {
    return await Http.post(`/authenticate`, params);
  },
};
