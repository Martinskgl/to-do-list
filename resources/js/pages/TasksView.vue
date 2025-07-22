<template>
  <div class="tasks-page">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <span class="navbar-brand">📝 Todo App</span>
        <button @click="logout" class="btn btn-outline-light btn-sm">
          Logout
        </button>
      </div>
    </nav>

    <TaskList @logout="logout" />
  </div>
</template>

<script>
import TaskList from './components/tasks/TaskList.vue'
import AuthService from '../Auth/AuthService'

export default {
  name: 'TasksView',
  components: {
    TaskList
  },
  methods: {
    async logout() {
      await AuthService.logout()
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
.tasks-page {
  min-height: 100vh;
}
</style>
