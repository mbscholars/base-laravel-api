<script>
//vue3 boilerplate
import { ref } from 'vue'
import BaseInput from '@/components/BaseInput.vue';
import BaseButton from '@/components/BaseButton.vue';
import { ChevronLeftIcon } from "@vue-hero-icons/outline"
export default {

    setup(props, context) {
        const email = ref(null);
        const password = ref(null);
        const submit = function () {
            alert("login");
        };

        return {
            email,
            password,
            submit
        };
    },

    meta: {
        permission: 'auth.read',
        redirectIfLoggedIn: false,
    },
    components: { BaseInput, BaseButton, ChevronLeftIcon }
}
</script>
<template>
  <div class="flex min-h-screen flex-1">
    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
      <div class="mx-auto w-full max-w-sm lg:w-96">
        <div>
          <img class="h-28 w-auto" src="@/assets/images/logo.png" alt="ß" />
          <div class="flex items-left">
                <div class="text-sm leading-6">
                    <!-- <ChevronLeftIcon class="h-5 w-5 text-primary-500 mr-2" size="small" /> -->
                  <router-link :to="{name: 'login'}" class="font-semibold text-primary-600 hover:text-primary-500">Back to Login </router-link>
                </div>
              </div>
          <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Password Reset</h2>
          <p class="mt-2 text-sm leading-6 text-gray-500">
            A password reset link will be sent to your email address
             </p>
        </div>

        <div class="mt-10">
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






              <div>
                <BaseButton
                  action="Send Password Reset Link"
                  type="submit"
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
