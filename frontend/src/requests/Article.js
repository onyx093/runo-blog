import Http from './Http';

export default {
  index(params) {
    return Http.get('/articles', { params });
  },
  show(id) {
    return Http.get(`/articles/${id}`);
  },
  store(params) {
    return Http.post('/articles', params);
  },
  update(articleId, params) {
    return Http.put(`/articles/${articleId}`, params);
  },
};
