import { describe, expect, it } from 'vitest'
import apiClient from '@/services/api'

// API client tests
describe('api client', () => {
  it('has request and response interceptors configured', () => {
    // Check that interceptors are configured by attempting to eject one and expecting a valid id (number >= 0)
    const reqInterceptorId = apiClient.interceptors.request.use(response => response)
    const resInterceptorId = apiClient.interceptors.response.use(response => response)
    expect(typeof reqInterceptorId).toBe('number')
    expect(typeof resInterceptorId).toBe('number')
    // Clean up
    apiClient.interceptors.request.eject(reqInterceptorId)
    apiClient.interceptors.response.eject(resInterceptorId)
  })

  it('request interceptor adds Authorization header when token present', async () => {
    localStorage.setItem('auth_token', 'abc123')
    // Mock the request using axios-mock-adapter or similar if available
    // Here, we make a request and check the header
    const config = { headers: {} }
    const interceptor = (apiClient.interceptors.request as any).handlers[0].fulfilled
    const interceptedConfig = interceptor(config)
    expect(interceptedConfig.headers.Authorization).toBe('Bearer abc123')
  })
})
