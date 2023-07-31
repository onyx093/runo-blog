import Http from './Http';

export default {
  async index() {
    return await Http.get('/articles');
  },
  async show(id) {
    return await Http.get(`/articles/${id}`);
  },
};
