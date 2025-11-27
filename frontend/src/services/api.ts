import axios, { type AxiosInstance, AxiosError } from 'axios'
import type { ApiError } from '@/types'

const apiClient: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

// Request interceptor for adding auth token
apiClient.interceptors.request.use(
  config => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => Promise.reject(error)
)

// Response interceptor for error handling
apiClient.interceptors.response.use(
  response => response,
  (error: AxiosError<ApiError>) => {
    const apiError: ApiError = {
      message: error.response?.data?.message || error.message || 'An error occurred',
      code: error.response?.data?.code,
      details: error.response?.data?.details
    }
    return Promise.reject(apiError)
  }
)

export default apiClient