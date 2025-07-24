<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <span class="navbar-brand">📝 Todo App - Admin Panel</span>
      <div class="d-flex gap-2">
        <span
          class="avatar-circle mt-1 bg-light text-dark fw-bold"
          v-if="user"
          style="
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.1rem;
          "
        >
          {{ user.name.charAt(0).toUpperCase() }}
        </span>
        <span class="navbar-text me-3" v-if="user">
          {{ user.name }}
        </span>
        <button @click="goBack" class="btn btn-outline-light btn-sm">← Voltar</button>
        <button
          v-if="isAdmin()"
          @click="$router.push('/admin/users')"
          class="btn btn-success btn-outline-light btn-sm"
        >
          Usuários
        </button>
        <button @click="logout" class="btn btn-danger btn-outline-light btn-sm">Logout</button>
      </div>
    </div>
  </nav>
</template>

<script>
  import AuthService from '../../../Auth/AuthService';

  export default {
    name: 'Header',
    data() {
      return {
        user: null,
      };
    },
    async mounted() {
      try {
        console.log('teste');
        const res = await axios.get('/api/me', {
          headers: {
            Authorization: `Bearer ${AuthService.getToken()}`,
          },
        });
        this.user = res.data;
      } catch (e) {
        this.user = null;
      }
    },
    methods: {
      async logout() {
        await AuthService.logout();
        this.$router.push('/login');
      },

      goBack() {
        this.$router.go(-1);
      },
      isAdmin() {
        return AuthService.isAdmin();
      },
    },
  };
</script>

<style scoped>
  .navbar-brand {
    font-weight: 600;
  }
  .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }
</style>
