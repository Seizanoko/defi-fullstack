import apiClient from './api'
import type { LoginRequest, RegisterRequest, AuthResponse } from '@/types'

export const authService = {
  async login(credentials: LoginRequest): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>('/auth/login', credentials)
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
    }
    return response.data
  },

  async register(data: RegisterRequest): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>('/auth/register', data)
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