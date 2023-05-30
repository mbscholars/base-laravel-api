import { defineStore } from 'pinia'
import login from '@/composables/useApi'

 export const User = defineStore('user', {
  state: () => {
    return {
        user: null,
        token: null,
        permissions: ['auth.read'],
        roles: [],
    }
  },
  getters: {
    getToken(state) {
      return state.token
    },
    getUser(state) {
      return state.user
    },
    isLogged(state) {
        return !! state.user
    },
    getPermissions(state) {
      return state.permissions
    },
    canNavigate(state) {
        return (to) => {
            if(to.meta?.permission === undefined){
                return false;
            }
        if(state.permissions.includes(to.meta?.permission)){
            return true
        }
        return false
    }
  }
  },
  actions: {
    // any amount of arguments, return a promise or not
    login(email,password) {
     const { data, error, loading } = login(email, password)
      this.user = data.user
      this.permissions = data.permissions
      this.roles = data.roles
      this.token = data.token
    },

  },
})
