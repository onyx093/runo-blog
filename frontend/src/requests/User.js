import Http from './Http';

export default {
  async register(params) {
    return await Http.post(`/register`, params);
  },
};
