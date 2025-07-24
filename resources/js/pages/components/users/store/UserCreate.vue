<template>
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Criar</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="createUser">
        <div class="row">
          <div class="form-group col-md-6">
            <label for="name">Nome *</label>
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
          <div class="form-group col-md-6">
            <label for="email">Email *</label>
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
        </div>
        <div class="row mt-3">
          <div class="form-group col-md-6">
            <label for="password">Senha *</label>
            <input
              type="password"
              id="password"
              v-model="form.password"
              class="form-control"
              :class="{ 'is-invalid': errors.password }"
              placeholder="Digite a senha"
              required
            />
            <div v-if="errors.password" class="invalid-feedback">
              {{ errors.password[0] }}
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="role">Role *</label>
            <select id="role" v-model="form.role" class="form-select" required>
              <option value="" disabled>Selecione uma role</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.label }}
              </option>
            </select>
            <div v-if="errors.role" class="invalid-feedback">
              {{ errors.role[0] }}
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Salvar</button>
          </div>
        </div>
        <div v-if="successMessage" class="alert alert-success mt-3">✅ {{ successMessage }}</div>
        <div v-if="errorMessage" class="alert alert-danger mt-3">❌ {{ errorMessage }}</div>
      </form>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'UserCreate',
    emits: ['userCreated'],
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: '',
          role: '',
        },
        roles: [],
        errors: {},
        loading: false,
        successMessage: '',
        errorMessage: '',
      };
    },
    async mounted() {
      const response = await axios.get('/api/roles');
      this.roles = JSON.parse(JSON.stringify(response.data.data));
    },
    methods: {
      async createUser() {
        this.loading = true;
        this.successMessage = '';
        this.errorMessage = '';
        this.errors = {};
        try {
          const response = await axios.post('/api/users', {
            name: this.form.name,
            email: this.form.email,
            password: this.form.password,
            role_id: this.form.role,
          });
          this.successMessage = 'Usuário criado com sucesso!';
          this.$emit('userCreated', response.data);
          this.form.name = '';
          this.form.email = '';
          this.form.password = '';
          this.form.role = '';
        } catch (error) {
          if (error.response && error.response.data && error.response.data.errors) {
            this.errors = error.response.data.errors;
            this.errorMessage = 'Por favor, corrija os erros no formulário';
          } else {
            this.errorMessage = 'Erro ao criar usuário.';
          }
        } finally {
          this.loading = false;
        }
      },
    },
  };
</script>
