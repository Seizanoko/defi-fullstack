import { describe, expect, it, beforeEach, vi } from 'vitest'
import { authService } from '@/services/authService'
import apiClient from '@/services/api'

beforeEach(() => {
  vi.restoreAllMocks()
  localStorage.clear()
})

// authService tests
describe('authService', () => {
  it('login stores token and returns data', async () => {
    const mockResponse = { data: { token: 't123', user: { id: 1 } } }
    vi.spyOn(apiClient, 'post').mockResolvedValueOnce(mockResponse as any)

    const result = await authService.login({ username: 'u', password: 'p' })
    expect(result).toEqual(mockResponse.data)
    expect(localStorage.getItem('auth_token')).toBe('t123')
  })

  it('register stores token and returns data', async () => {
    const mockResponse = { data: { token: 'r123', user: { id: 2 } } }
    vi.spyOn(apiClient, 'post').mockResolvedValueOnce(mockResponse as any)

    const result = await authService.register({ username: 'u', password: 'p' })
    expect(result).toEqual(mockResponse.data)
    expect(localStorage.getItem('auth_token')).toBe('r123')
  })

  it('logout removes token and isAuthenticated reflects that', () => {
    localStorage.setItem('auth_token', 'x')
    expect(authService.isAuthenticated()).toBe(true)

    authService.logout()
    expect(localStorage.getItem('auth_token')).toBeNull()
    expect(authService.isAuthenticated()).toBe(false)
  })
})
