<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="mb-0">Login</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="handleLogin">
              <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  class="form-control"
                  :class="{ 'is-invalid': errors.email }"
                  required
                />
                <div v-if="errors.email" class="invalid-feedback">
                  {{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}
                </div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Senha:</label>
                <input
                  type="password"
                  id="password"
                  v-model="form.password"
                  class="form-control"
                  :class="{ 'is-invalid': errors.password }"
                  required
                />
                <div v-if="errors.password" class="invalid-feedback">
                  {{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}
                </div>
              </div>

              <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                  <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                  {{ loading ? 'Entrando...' : 'Entrar' }}
                </button>
              </div>

              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>

              <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import AuthService from './AuthService';

  export default {
    name: 'LoginForm',
    data() {
      return {
        form: {
          email: '',
          password: '',
        },
        errors: {},
        loading: false,
        errorMessage: '',
        successMessage: '',
      };
    },
    methods: {
      async handleLogin() {
        this.clearMessages();
        this.loading = true;

        try {
          const result = await AuthService.login(this.form.email, this.form.password);

          if (result.success) {
            this.successMessage = 'Login realizado com sucesso!';

            setTimeout(() => {
              this.$emit('login-success');
            }, 1000);
          } else {
            this.errorMessage = result.message;

            if (result.errors) {
              this.errors = result.errors;
            }
          }
        } catch (error) {
          console.error('Erro no login:', error);
          this.errorMessage = 'Erro inesperado. Tente novamente.';
        } finally {
          this.loading = false;
        }
      },

      clearMessages() {
        this.errors = {};
        this.errorMessage = '';
        this.successMessage = '';
      },
    },
    mounted() {
      if (AuthService.isAuthenticated()) {
        this.$emit('login-success');
      }
    },
  };
</script>

<style scoped>
  .card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
  }

  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }

  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }

  .spinner-border-sm {
    width: 1rem;
    height: 1rem;
  }
</style>
