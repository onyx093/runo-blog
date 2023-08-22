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
  withTagId(tag) {
    return Http.get(`/articles?tag_ids[]=${tag}`);
  },
  withTagName(tag) {
    return Http.get(`/articles?tag_names[]=${tag}`);
  },
};
