import Http from './Http';

export default {
  async index(params) {
    return await Http.get('/articles', { params });
  },
  async show(id) {
    return await Http.get(`/articles/${id}`);
  },
};
