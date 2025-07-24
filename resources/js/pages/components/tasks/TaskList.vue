<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Minhas Tasks</h2>
      <div class="d-flex gap-2">
        <button v-if="isAdmin" @click="goToCreateTask" class="btn btn-success btn-sm">
          + Criar Task
        </button>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-8">
        <input
          type="text"
          v-model="searchQuery"
          class="form-control"
          placeholder="🔍 Buscar tasks..."
        />
      </div>
      <div class="col-md-4">
        <select v-model="statusFilter" class="form-select">
          <option value="">Todos os Status</option>
          <option value="pending">Pendente</option>
          <option value="in_progress">Em Progresso</option>
          <option value="completed">Concluída</option>
          <option value="cancelled">Cancelada</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="text-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div v-else>
      <div v-if="filteredTasks.length === 0" class="alert alert-info">Nenhuma task encontrada.</div>

      <div v-else class="row">
        <div v-for="task in filteredTasks" :key="task.id" class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                  {{ task.title }}
                </h5>
                <button
                  v-if="isAdmin"
                  class="btn btn-outline-primary btn-sm ms-2"
                  title="Editar Task"
                  @click="$router.push(`/admin/tasks/edit/${task.id}`)"
                >
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button
                  class="btn btn-outline-primary btn-sm ms-2"
                  title="Editar Task"
                  @click="deleteTask(task.id)"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
              <p class="card-text">{{ task.describe }}</p>
              <div class="mb-1 text-muted small">
                <strong>Atribuído:</strong>
                {{ task.user?.name || task.user_name || 'N/A' }}
              </div>

              <div class="mb-2">
                <select
                  v-model="task.status"
                  @change="updateTaskStatus(task)"
                  :class="
                    'badge ' +
                    getStatusClass(task.status) +
                    ' form-select form-select-sm w-auto d-inline-block'
                  "
                >
                  <option value="pending">Pendente</option>
                  <option value="in_progress">Em Progresso</option>
                  <option value="completed">Concluída</option>
                  <option value="cancelled">Cancelada</option>
                </select>
              </div>
              <div v-if="task.expiration_date" class="text-muted small">
                Vence em: {{ formatDate(task.expiration_date) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="showToast"
      :class="
        'alert alert-dismissible position-fixed top-0 start-50 translate-middle-x mt-3 ' +
        (toastType === 'error' ? 'alert-danger' : 'alert-success')
      "
      style="z-index: 9999"
    >
      <strong v-if="toastType === 'error'">❌ {{ toastMessage }}</strong>
      <strong v-else>✅ {{ toastMessage }}</strong>
      <button type="button" class="btn-close" @click="hideToast"></button>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import AuthService from '../../../Auth/AuthService';

  export default {
    name: 'TaskList',
    data() {
      return {
        tasks: [],
        loading: true,
        error: '',
        searchQuery: '',
        statusFilter: '',
        showToast: false,
        toastMessage: '',
        toastType: 'success',
      };
    },
    computed: {
      isAdmin() {
        return AuthService.isAdmin();
      },

      deleteTask() {
        return async (taskId) => {
          try {
            await axios.delete(`/api/tasks/${taskId}`);
            this.tasks = this.tasks.filter((task) => task.id !== taskId);
            this.toastMessage = 'Task deletada com sucesso!';
            this.toastType = 'success';
            this.showToast = true;
            setTimeout(() => this.hideToast(), 2000);
          } catch (error) {
            console.error(error);
            this.error = 'Erro ao deletar task';
          }
        };
      },

      filteredTasks() {
        let filtered = this.tasks;

        if (this.searchQuery) {
          const query = this.searchQuery.toLowerCase();
          filtered = filtered.filter(
            (task) =>
              task.title.toLowerCase().includes(query) ||
              (task.describe && task.describe.toLowerCase().includes(query))
          );
        }

        if (this.statusFilter) {
          filtered = filtered.filter((task) => task.status === this.statusFilter);
        }

        return filtered;
      },
    },
    methods: {
      async loadTasks() {
        this.loading = true;
        this.error = '';

        try {
          let response;
          if (this.isAdmin) {
            response = await axios.get('/api/tasks');
            console.log(this.tasks, 'aqui');
          } else {
            response = await axios.get('api/tasks/user');
          }
          this.tasks = response.data.data || response.data;
        } catch (error) {
          this.error = 'Erro ao carregar tasks';
        } finally {
          this.loading = false;
        }
      },

      async updateTaskStatus(task) {
        try {
          const response = await axios.patch(`/api/tasks/${task.id}/status`, {
            status: task.status,
          });
          this.toastMessage = 'Status atualizado com sucesso!';
          this.toastType = 'success';
          this.showToast = true;
          setTimeout(() => this.hideToast(), 2000);
        } catch (error) {
          if (error.response && error.response.data && error.response.data.message) {
            this.toastMessage = error.response.data.message;
            this.toastType = 'error';
            this.showToast = true;
            setTimeout(() => this.hideToast(), 3000);
          } else {
            this.error = 'Erro ao atualizar status da task';
          }
        }
      },

      getStatusClass(status) {
        const classes = {
          pending: 'bg-warning text-dark',
          in_progress: 'bg-primary',
          completed: 'bg-success',
          cancelled: 'bg-danger',
        };
        return classes[status] || 'bg-secondary';
      },

      getStatusText(status) {
        const texts = {
          pending: 'Pendente',
          in_progress: 'Em Progresso',
          completed: 'Concluída',
          cancelled: 'Cancelada',
        };
        return texts[status] || status;
      },

      formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
      },

      async logout() {
        await AuthService.logout();
        this.$emit('logout');
      },

      goToCreateTask() {
        this.$router.push('/admin/tasks/create');
      },

      checkForSuccessMessage() {
        const message = localStorage.getItem('taskCreatedMessage');
        if (message) {
          this.toastMessage = message;
          this.showToast = true;
          localStorage.removeItem('taskCreatedMessage');

          setTimeout(() => {
            this.hideToast();
          }, 3000);
        }
      },

      hideToast() {
        this.showToast = false;
      },
    },
    mounted() {
      this.loadTasks();
      this.checkForSuccessMessage();
    },
  };
</script>

<style scoped>
  .card {
    transition: transform 0.2s;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .badge {
    font-size: 0.8em;
  }

  .pagination .page-link {
    border-radius: 0.25rem;
    margin: 0 2px;
  }

  .spinner-border {
    width: 3rem;
    height: 3rem;
  }

  .btn-success {
    background-color: #28a745;
    border-color: #28a745;
  }

  .btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
  }

  .d-flex.gap-2 > * {
    margin-right: 0.5rem;
  }

  .d-flex.gap-2 > *:last-child {
    margin-right: 0;
  }

  /* Filtros */
  .form-control:focus,
  .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }
</style>
