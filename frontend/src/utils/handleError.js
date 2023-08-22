import { toast } from 'vue3-toastify';

export default (error, errorStore = undefined) => {
  if (error.response.status === 422) {
    if (errorStore) {
      errorStore.setErrors(error.response.data.errors);
    }
    toast.error('Validation error. Please, check your inputs.');
  } else if (error.response.status === 401) {
    toast.error('Unexpected error occurred. Please, try again later.');
  } else {
    console.log(error.response.data.errors);
    toast.error('Unexpected error occurred. Please, try again later.');
  }
};
