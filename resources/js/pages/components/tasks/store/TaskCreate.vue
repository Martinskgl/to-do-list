<template>
  <div class="task-create">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">📝 Criar</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="createTask">
          <div class="mb-3">
            <label for="title" class="form-label">Título *</label>
            <input
              type="text"
              id="title"
              v-model="form.title"
              class="form-control"
              :class="{ 'is-invalid': errors.title }"
              placeholder="Digite o título da task"
              required
            />
            <div v-if="errors.title" class="invalid-feedback">
              {{ errors.title[0] }}
            </div>
          </div>

          <div class="mb-3">
            <label for="describe" class="form-label">Descrição</label>
            <textarea
              id="describe"
              v-model="form.describe"
              class="form-control"
              :class="{ 'is-invalid': errors.describe }"
              rows="3"
              placeholder="Descreva os detalhes da task (opcional)"
            ></textarea>
            <div v-if="errors.describe" class="invalid-feedback">
              {{ errors.describe[0] }}
            </div>
          </div>

          <div class="mb-3">
            <label for="user_id" class="form-label">Responsável</label>
            <select id="user_id" v-model="form.user_id" class="form-select">
              <option value="" disabled>Selecione o responsável</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }} ({{ user.email }})
              </option>
            </select>
            <div v-if="errors.user_id" class="invalid-feedback d-block">
              {{ errors.user_id[0] }}
            </div>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" v-model="form.status" class="form-select">
              <option value="pending">Pendente</option>
              <option value="in_progress">Em Progresso</option>
              <option value="completed">Concluída</option>
              <option value="cancelled">Cancelada</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="expiration_date" class="form-label">Data de Expiração</label>
            <input
              type="datetime-local"
              id="expiration_date"
              v-model="form.expiration_date"
              class="form-control"
              :min="minDate"
            />
            <div class="form-text">Deixe em branco se não houver prazo específico</div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              {{ loading ? 'Criando...' : 'Criar Task' }}
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
    name: 'TaskCreate',
    emits: ['task-created', 'cancel'],
    data() {
      return {
        form: {
          title: '',
          describe: '',
          status: 'pending',
          expiration_date: '',
          user_id: '',
        },
        users: [],
        loading: false,
        errors: {},
        successMessage: '',
        errorMessage: '',
      };
    },
    computed: {
      minDate() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
      },
    },
    async mounted() {
      try {
        const response = await axios.get('/api/users');
        this.users = Array.isArray(response.data)
          ? response.data.filter((u) => u && u.id && u.name)
          : [];
      } catch (e) {
        this.users = [];
      }
    },
    methods: {
      async createTask() {
        this.loading = true;
        this.errors = {};
        this.successMessage = '';
        this.errorMessage = '';

        try {
          const taskData = { ...this.form };

          if (taskData.expiration_date) {
            taskData.expiration_date = new Date(taskData.expiration_date).toISOString();
          }

          Object.keys(taskData).forEach((key) => {
            if (taskData[key] === '' || taskData[key] === null) {
              delete taskData[key];
            }
          });

          const response = await axios.post('/api/tasks', taskData);

          this.successMessage = 'Task criada com sucesso!';

          this.$emit('task-created', response.data.data || response.data);

          setTimeout(() => {
            this.resetForm();
            this.$emit('cancel');
          }, 2000);
        } catch (error) {
          console.error('Erro ao criar task:', error);

          if (error.response?.status === 422) {
            this.errors = error.response.data.errors || {};
            this.errorMessage = 'Por favor, corrija os erros no formulário';
          } else {
            this.errorMessage = error.response?.data?.message || 'Erro ao criar task';
          }
        } finally {
          this.loading = false;
        }
      },

      resetForm() {
        this.form = {
          title: '',
          describe: '',
          status: 'pending',
          expiration_date: '',
        };
        this.errors = {};
        this.successMessage = '';
        this.errorMessage = '';
      },
    },
  };
</script>

<style scoped>
  .task-create {
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
