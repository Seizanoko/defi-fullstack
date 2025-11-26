import axios from 'axios'
import type { LoginRequest, RegisterRequest, AuthResponse } from '@/types'

// Create a separate axios instance for auth (outside OpenAPI spec)
const authClient = axios.create({
  baseURL: 'https://localhost/api/auth',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json'
  }
})

export const authService = {
  async login(credentials: LoginRequest): Promise<AuthResponse> {
    const response = await authClient.post<AuthResponse>('/login', credentials)
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
    }
    return response.data
  },

  async register(data: RegisterRequest): Promise<AuthResponse> {
    const response = await authClient.post<AuthResponse>('/register', data)
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
    }
    return response.data
  },

  logout() {
    localStorage.removeItem('auth_token')
  },

  isAuthenticated(): boolean {
    return !!localStorage.getItem('auth_token')
  }
}