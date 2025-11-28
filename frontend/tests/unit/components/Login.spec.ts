import { describe, it, expect, vi, beforeEach } from 'vitest'
import { shallowMount } from '@vue/test-utils'
import { V_STUBS } from '../test-utils'

vi.mock('@/services/authService', () => ({
  authService: {
    login: vi.fn(),
  },
}))

import Login from '@/components/Login.vue'
import { authService } from '@/services/authService'

const GLOBAL_STUBS = ['v-card', 'v-form', 'v-text-field', 'v-btn', 'v-alert', 'v-divider']

beforeEach(() => {
  vi.restoreAllMocks()
})

// Login component tests
describe('Login component', () => {
  it('emits login-success on successful login', async () => {
    ;(authService.login as any).mockResolvedValue({ token: 'tok' })
    const wrapper = shallowMount(Login, { global: { stubs: V_STUBS } })

    // set form validity and values
    ;(wrapper.vm as any).valid = true
    ;(wrapper.vm as any).form.username = 'user'
    ;(wrapper.vm as any).form.password = 'pass'

    // call the handler directly
    await (wrapper.vm as any).handleLogin()

    expect(authService.login).toHaveBeenCalledWith({ username: 'user', password: 'pass' })
    expect(wrapper.emitted()['login-success']).toBeTruthy()
  })

  it('sets error message when login fails', async () => {
    ;(authService.login as any).mockRejectedValue({ message: 'Invalid credentials' })
    const wrapper = shallowMount(Login, { global: { stubs: V_STUBS } })

    ;(wrapper.vm as any).valid = true
    ;(wrapper.vm as any).form.username = 'user'
    ;(wrapper.vm as any).form.password = 'bad'

    await (wrapper.vm as any).handleLogin()

    expect((wrapper.vm as any).error).toBe('Invalid credentials')
  })
})
