<template>
  <div class="login-page">
    <LoginForm @login-success="handleLoginSuccess" />
  </div>
</template>

<script>
import LoginForm from '../Auth/LoginForm.vue'

export default {
  name: 'LoginView',
  components: {
    LoginForm
  },
  methods: {
    handleLoginSuccess() {
      this.$router.push('/tasks')
    }
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
