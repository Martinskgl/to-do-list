<template>
  <div class="user-edit">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">✏️ Editar Usuário</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="updateUser">
          <div class="mb-3">
            <label for="name" class="form-label">Nome *</label>
            <input
              type="text"
              id="name"
              v-model="form.name"
              class="form-control"
              :class="{ 'is-invalid': errors.name }"
              placeholder="Digite o nome do usuário"
              required
            />
            <div v-if="errors.name" class="invalid-feedback">
              {{ errors.name[0] }}
            </div>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input
              type="email"
              id="email"
              v-model="form.email"
              class="form-control"
              :class="{ 'is-invalid': errors.email }"
              placeholder="Digite o email do usuário"
              required
            />
            <div v-if="errors.email" class="invalid-feedback">
              {{ errors.email[0] }}
            </div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Senha *</label>
            <input
              type="password"
              id="password"
              v-model="form.password"
              class="form-control"
              :class="{ 'is-invalid': errors.password }"
              placeholder="Digite a senha (deixe em branco para não alterar)"
            />
            <div v-if="errors.password" class="invalid-feedback">
              {{ errors.password[0] }}
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Perfil</label>
            <select id="role" v-model="form.role_id" class="form-select">
              <option value="" disabled>Selecione o perfil</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.label }}
              </option>
            </select>
            <div v-if="errors.role_id" class="invalid-feedback d-block">
              {{ errors.role_id[0] }}
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              {{ loading ? 'Salvando...' : 'Salvar Alterações' }}
            </button>
            <button
              type="button"
              class="btn btn-outline-secondary"
              @click="$emit('cancel')"
              :disabled="loading"
            >
              Cancelar
            </button>
          </div>
        </form>
        <div v-if="successMessage" class="alert alert-success mt-3">✅ {{ successMessage }}</div>
        <div v-if="errorMessage" class="alert alert-danger mt-3">❌ {{ errorMessage }}</div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'UserEdit',
    props: {
      user: {
        type: Object,
        required: true,
      },
    },
    emits: ['user-updated', 'cancel'],
    data() {
      return {
        form: {
          name: '',
          email: '',
          role_id: '',
          password: '',
        },
        roles: [],
        loading: false,
        errors: {},
        successMessage: '',
        errorMessage: '',
      };
    },
    mounted() {
      this.form = {
        name: this.user.name || '',
        email: this.user.email || '',
        role_id: this.user.role_id || this.user.role?.id || '',
      };
      axios
        .get('/api/roles')
        .then((response) => {
          let roles = response.data;
          if (roles.data) roles = roles.data;
          this.roles = Array.isArray(roles)
            ? roles.filter((r) => r && r.id && (r.label || r.name))
            : [];
        })
        .catch(() => {
          this.roles = [];
        });
    },
    methods: {
      async updateUser() {
        this.loading = true;
        this.errors = {};
        this.successMessage = '';
        this.errorMessage = '';

        try {
          const userData = { ...this.form };
          Object.keys(userData).forEach((key) => {
            if (userData[key] === '' || userData[key] === null) {
              delete userData[key];
            }
          });

          const response = await axios.put(`/api/users/${this.user.id}`, userData);

          this.successMessage = 'Usuário atualizado com sucesso!';
          this.$emit('user-updated', response.data.data || response.data);
          const user = await axios
            .get(`/api/users/${this.user.id}`)
            .then((response) => response.data.data || response.data)
            .catch(() => null);

          if (user) {
            AuthService.setUser(user);
          }

          setTimeout(() => {
            this.$emit('cancel');
          }, 2000);
        } catch (error) {
          if (error.response?.status === 422) {
            this.errors = error.response.data.errors || {};
            this.errorMessage = 'Por favor, corrija os erros no formulário';
          } else {
            this.errorMessage = error.response?.data?.message || 'Erro ao atualizar usuário';
          }
        } finally {
          this.loading = false;

          this.resetForm();
        }
      },
      resetForm() {
        this.form = {
          name: this.user.name || '',
          email: this.user.email || '',
          role_id: this.user.role_id || '',
        };
        this.errors = {};
        this.successMessage = '';
        this.errorMessage = '';
      },
    },
  };
</script>

<style scoped>
  .user-edit {
    max-width: 600px;
    margin: 0 auto;
  }
  .card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
  }
  .form-label {
    font-weight: 600;
    color: #495057;
  }
  .form-control:focus,
  .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }
  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }
  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }
  .alert {
    border: none;
    border-radius: 0.375rem;
  }
  .alert-success {
    background-color: #d1edff;
    color: #0c5460;
  }
  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }
  .spinner-border-sm {
    width: 1rem;
    height: 1rem;
  }
</style>
