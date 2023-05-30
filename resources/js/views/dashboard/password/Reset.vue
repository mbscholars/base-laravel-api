<script>
//vue3 boilerplate
import { ref } from 'vue'
import BaseInput from '@/components/BaseInput.vue';
import logoImage from '@/assets/images/logo.png';
import BaseButton from '@/components/BaseButton.vue';
export default {
    setup(props, context) {
        const form = ref({});
        const submit = function () {
            alert("submit");
        };
        return {

            form,

        };
    },
    components: { BaseInput, BaseButton },
    meta: {
        permission: 'auth.read',
        redirectIfLoggedIn: false,
    }
}
</script>
<template>
  <div class="flex min-h-screen flex-1">
    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
      <div class="mx-auto w-full max-w-sm lg:w-96">
        <div>
          <img class="h-28 w-auto" src="@/assets/images/logo.png" alt="ß" />
          <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Reset Your Password</h2>

        </div>

        <div class="mt-10">
          <div class="space-y-6">

             <BaseInput
                label="Password"
                id="password"
                name="password"
                type="password"
                v-model="form.password"
                required
                autofocus
                autocomplete
                placeholder="Enter your password"
                />
             <BaseInput
                label="Re-enter Password"
                id="password"
                name="password"
                type="password"
                v-model="form.password_confirmation"
                required
                autofocus
                autocomplete
                placeholder="Re-enter Password"
                />



              <div class="flex items-right justify-end">



              </div>

              <div>
                <BaseButton
                  action="Reset Password"
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
