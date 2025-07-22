<template>
  <div class="tasks-page">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <span class="navbar-brand">📝 Todo App</span>
        <button @click="logout" class="btn btn-outline-light btn-sm">
          Logout
        </button>
         <button v-if="isAdmin" @click="gotToIndexUser" class="btn btn-outline-light btn-sm">
          Usuários
        </button>
    </div>
    </nav>
    <UserList/>
  </div>
</template>

<script>
import AuthService from '../Auth/AuthService'
import UserList from './components/users/UserList.vue'

export default {
  name: 'TasksView',
  components: {
    UserList
  },
  methods: {
    async logout() {
      await AuthService.logout()
      this.$router.push('/login')
    },

    gotToIndexUser() {
      this.$router.push('/admin/users')
    }

  },
  computed: {
    isAdmin() {
      return AuthService.isAdmin()
    }
  },
}
</script>

<style scoped>
.tasks-page {
  min-height: 100vh;
}
</style>


