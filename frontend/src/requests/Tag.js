import Http from './Http';

export default {
  index(params) {
    return Http.get('/tags', params);
  },
  show(id) {
    return Http.get(`/tags/${id}`);
  },
};
