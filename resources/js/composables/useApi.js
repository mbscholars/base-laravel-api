// export default function login(email, password) {
//     const data = ref(null)
//     const error = ref(null)
//     const loading = ref(false)
//     loading.value = true
//     // axios.post('/login', {email, password}).then( res => {
//     //     data.value = res.data
//     // }).catch (err => {
//     //     error.value = err.message
//     // }).finally(() => {
//     //     loading.value = false
//     // })


//     return { data, error, loading }
// }

import axios from 'axios';
import { ref } from 'vue';

export default function userLogin() {
  const data = ref(null);
  const error = ref(null);
  const loading = ref(false);

    async function login(email, password) {
        loading.value = true;
        const response =  await axios.post('/login', {email, password}).then( res => {
          data.value = response.data;
        }). catch (err => {
          error.value = err.response.data.message || err.response.message;
        }).finally(() => {
            loading.value = false;
        });
}

  return {
    data,
    error,
    login,
    loading,
  }
}
