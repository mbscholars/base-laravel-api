<script>
//vue3 boilerplate
import { ref,reactive } from 'vue'
import BaseInput from '../../components/BaseInput.vue';
import logoImage from '../../assets/images/logo.png';
import BaseButton from '../../components/BaseButton.vue';
import userLogin from '@/composables/useApi.js'
export default {
    setup(props, context) {
        const email = ref(null);
        const password = ref(null);
        const errors = ref({})
        const { data, loading, error, login } = userLogin();
        const submit = function () {
              login(email.value, password.value);

        };
        return {
            email,
            errors,
            loading,
            data,
            error,
            password,
            login,
            submit
        };
    },
    components: { BaseInput, BaseButton },
    meta: {
        layout: 'blank',
        permission: 'auth.read',
        redirectIfLoggedIn: true,
    }
}
</script>
<template>
  <div class="flex min-h-screen flex-1">
    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
      <div class="mx-auto w-full max-w-sm lg:w-96">
        <div>
          <img class="h-28 w-auto" src="../../assets/images/logo.png" alt="ß" />
          <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
          <p class="mt-2 text-sm leading-6 text-gray-500">
            Not a member?
            {{ ' ' }}
            <a href="#" class="font-semibold text-primary-600 hover:text-primary-500">Start a 14 day free trial</a>
          </p>
        </div>

        <div class="mt-10"> {{  error }}
          <div class="space-y-6">
             <BaseInput
                label="Email address"
                id="email"
                name="email"
                type="email"
                v-model="email"
                required
                :error="errors"
                autofocus
                autocomplete
                placeholder="Enter your registered email address"
                />
             <BaseInput
                label="Password"
                id="password"
                name="password"
                type="password"
                v-model="password"
                required
                autofocus
                autocomplete
                placeholder="Enter your password"
                />



              <div class="flex items-right justify-end">
                <div class="text-sm leading-6">
                  <router-link :to="{name: 'password-recover'}" class="font-semibold text-primary-600 hover:text-primary-500">Forgot password?</router-link>
                </div>
              </div>
              <div>
                <BaseButton
                  action="Sign in"
                  type="submit"
                  :disabled="!email || !password || loading"
                  @submit="submit"
                  />
              </div>

          </div>


        </div>
      </div>
    </div>
    <div class="relative hidden w-0 flex-1 lg:block">
      <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80" alt="" />
    </div>
  </div>


</template>
