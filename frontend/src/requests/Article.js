import Http from './Http';

export default {
  index(params) {
    return Http.get('/articles', { params });
  },
  show(id) {
    return Http.get(`/articles/${id}`);
  },
};
