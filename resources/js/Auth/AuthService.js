import axios from 'axios';

const API_BASE_URL = 'http://localhost/api';

class AuthService {
  constructor() {
    this.setupAxiosInterceptor();
  }

  async login(email, password) {
    try {
      const response = await axios.post(`${API_BASE_URL}/login`, {
        email,
        password,
      });

      const { token, user } = response.data;
      this.setToken(token);
      this.setUser(user);

      return { success: true };
    } catch (error) {
      console.error('Erro de login:', error.response?.data);

      if (error.response?.status === 422) {
        const errors = error.response.data.errors;
        const firstError = Object.values(errors)[0]?.[0] || 'Dados inválidos';
        return {
          success: false,
          message: firstError,
          errors: errors,
        };
      }

      return {
        success: false,
        message: error.response?.data?.message || 'Erro no login',
      };
    }
  }

  async logout() {
    try {
      await axios.post(`${API_BASE_URL}/logout`);
    } finally {
      this.clearAuth();
    }
  }

  setToken(token) {
    localStorage.setItem('auth_token', token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }

  getToken() {
    return localStorage.getItem('auth_token');
  }

  isAuthenticated() {
    return !!this.getToken();
  }

  clearAuth() {
    localStorage.removeItem('auth_token');
    delete axios.defaults.headers.common['Authorization'];
  }

  setUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
  }

  getUser() {
    return JSON.parse(localStorage.getItem('user'));
  }

  isAdmin() {
    const user = this.getUser();
    return user.role?.slug === 'admin';
  }

  setupAxiosInterceptor() {
    axios.interceptors.request.use((config) => {
      const token = this.getToken();
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    });

    axios.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          this.clearAuth();
          window.location.href = '/';
        }
        return Promise.reject(error);
      }
    );
  }
}

export default new AuthService();
