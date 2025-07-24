<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Usuários</h2>
      <div class="d-flex gap-2">
        <button v-if="isAdmin" @click="goToCreateUser" class="btn btn-success btn-sm">
          + Criar Usuário
        </button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Avatar</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Role</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>
              <span class="avatar-circle">{{ user.name.charAt(0).toUpperCase() }}</span>
            </td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <span class="badge bg-primary">{{ user.role?.label || 'Sem Role' }}</span>
              {{ console.log(user.role) }}
            </td>
            <td>
              <button
                v-if="user.id !== loggedUser.id"
                @click="$router.push(`/admin/users/edit/${user.id}`)"
                class="btn btn-sm btn-outline-primary me-2"
                title="Editar"
              >
                <i class="fa-solid fa-pencil"></i>
              </button>
              <button
                @click="deleteUser(user.id)"
                class="btn btn-sm btn-outline-danger"
                title="Excluir"
              >
                <i class="fa-solid fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import AuthService from '../../../Auth/AuthService';

  export default {
    name: 'TasksView',
    data() {
      return {
        users: [],
        loggedUser: AuthService.getUser() || {},
      };
    },
    methods: {
      async logout() {
        await AuthService.logout();
        this.$router.push('/login');
      },

      async deleteUser(id) {
        if (confirm('Tem certeza que deseja excluir este usuário?')) {
          await axios.delete(`/api/users/${id}`);
          this.users = this.users.filter((u) => u.id !== id);
        }
      },

      async loadUser() {
        try {
          const response = await axios.get('/api/users');
          this.users = response.data;
        } catch (error) {
          console.error('Erro ao carregar usuários:', error);
        }
      },

      gotToIndexUser() {
        this.$router.push('/admin/users');
      },

      gotToIndexTask() {
        this.$router.push('/tasks');
      },

      goToCreateUser() {
        this.$router.push('/admin/users/create');
      },
    },
    computed: {
      isAdmin() {
        return AuthService.isAdmin();
      },
    },
    mounted() {
      this.loadUser();
    },
  };

  // const users = ref([])

  // onMounted(async () => {
  //   const response = await axios.get('/api/users')
  //   users.value = response.data
  // })

  // function editUser(user) {
  //   alert('Editar usuário: ' + user.name)
  // }
</script>

<style scoped>
  /* Estilos para tabela e avatar */
  .user-list-container {
    max-width: 900px;
    margin: 0 auto;
  }
  .avatar-circle {
    background: #0d6efd;
    color: #fff;
    font-size: 1.2rem;
    font-weight: bold;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .badge {
    font-size: 0.95rem;
  }
</style>
