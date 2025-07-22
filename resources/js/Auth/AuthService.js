import axios from 'axios'

const API_BASE_URL = 'http://localhost/api'

class AuthService {
    constructor() {
        this.setupAxiosInterceptor()
    }

    async login(email, password) {
        try {
            const response = await axios.post(`${API_BASE_URL}/login`, {
                email, password
            })
            
            const { token } = response.data
            this.setToken(token)
            
            return { success: true }
        } catch (error) {
            return { 
                success: false, 
                message: error.response?.data?.message || 'Erro no login' 
            }
        }
    }

    async logout() {
        try {
            await axios.post(`${API_BASE_URL}/logout`)
        } finally {
            this.clearAuth()
        }
    }

    setToken(token) {
        localStorage.setItem('auth_token', token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    getToken() {
        return localStorage.getItem('auth_token')
    }

    isAuthenticated() {
        return !!this.getToken()
    }

    clearAuth() {
        localStorage.removeItem('auth_token')
        delete axios.defaults.headers.common['Authorization']
    }

    setupAxiosInterceptor() {
        axios.interceptors.request.use(config => {
            const token = this.getToken()
            if (token) {
                config.headers.Authorization = `Bearer ${token}`
            }
            return config
        })

        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response?.status === 401) {
                    this.clearAuth()
                    window.location.href = '/'
                }
                return Promise.reject(error)
            }
        )
    }
}

export default new AuthService()