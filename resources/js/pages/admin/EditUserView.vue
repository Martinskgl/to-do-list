<template>
  <div class="create-task-page">
    <Header />
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-12">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h2>Editar Usuário</h2>
              <p class="text-muted mb-0">Altere os dados do usuário abaixo</p>
            </div>
          </div>

          <div v-if="error" class="alert alert-danger">
            {{ error }}
          </div>
          <UserEdit
            v-if="user && !error"
            :user="user"
            @user-updated="onUserUpdated"
            @cancel="goBack"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import AuthService from '../../Auth/AuthService';
  import UserEdit from '../components/users/update/UserEdit.vue';
  import axios from 'axios';
  import Header from '../components/layout/header.vue';

  export default {
    name: 'EditUserView',
    components: {
      Header,
      UserEdit,
    },
    data() {
      return {
        user: null,
        loading: true,
        error: '',
      };
    },
    methods: {
      async logout() {
        await AuthService.logout();
        this.$router.push('/login');
      },

      goBack() {
        this.$router.go(-1);
      },

      async fetchUser() {
        this.loading = true;
        this.error = '';
        try {
          const id = this.$route.params.id;
          const response = await axios.get(`/api/users/${id}`);
          this.user = response.data.data || response.data;
        } catch (e) {
          if (e.response && e.response.status === 404) {
            this.error = 'Usuário não encontrado.';
          } else {
            this.error = 'Erro ao carregar usuário.';
          }
        } finally {
          this.loading = false;
        }
      },

      onUserUpdated(user) {
        localStorage.setItem('userUpdatedMessage', `Usuário "${user.name}" editado com sucesso!`);
        this.$nextTick(() => {
          this.$router.push('/admin/users');
        });
      },
    },

    async beforeRouteEnter(to, from, next) {
      if (!AuthService.isAuthenticated()) {
        next('/login');
        return;
      }

      if (!AuthService.isAdmin()) {
        next('/tasks');
        return;
      }

      next((vm) => {
        vm.fetchUser();
      });
    },
  };
</script>

<style scoped>
  .create-task-page {
    min-height: 100vh;
    background-color: #f8f9fa;
  }

  .navbar-brand {
    font-weight: 600;
  }

  h2 {
    color: #495057;
    font-weight: 600;
  }

  .text-muted {
    font-size: 0.9rem;
  }

  .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }
</style>
