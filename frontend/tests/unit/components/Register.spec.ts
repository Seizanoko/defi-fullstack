import { describe, it, expect, vi, beforeEach } from 'vitest'
import { shallowMount } from '@vue/test-utils'
import { V_STUBS } from '../test-utils'

vi.mock('@/services/authService', () => ({
  authService: {
    register: vi.fn(),
  },
}))

import Register from '@/components/Register.vue'
import { authService } from '@/services/authService'

const GLOBAL_STUBS = ['v-card', 'v-form', 'v-text-field', 'v-btn', 'v-alert', 'v-divider']

beforeEach(() => {
  vi.restoreAllMocks()
})

describe('Register component', () => {
  it('emits register-success on successful register', async () => {
    ;(authService.register as any).mockResolvedValue({ token: 'tok' })
    const wrapper = shallowMount(Register, { global: { stubs: V_STUBS } })

    ;(wrapper.vm as any).valid = true
    ;(wrapper.vm as any).form.username = 'new'
    ;(wrapper.vm as any).form.password = 'goodpass'
    ;(wrapper.vm as any).confirmPassword = 'goodpass'

    await (wrapper.vm as any).handleRegister()

    expect(authService.register).toHaveBeenCalledWith({ username: 'new', password: 'goodpass' })
    expect(wrapper.emitted()['register-success']).toBeTruthy()
  })

  it('shows error when register fails', async () => {
    ;(authService.register as any).mockRejectedValue({ message: 'fail' })

    const wrapper = shallowMount(Register, { global: { stubs: V_STUBS } })
    ;(wrapper.vm as any).valid = true
    ;(wrapper.vm as any).form.username = 'new'
    ;(wrapper.vm as any).form.password = 'bad'
    ;(wrapper.vm as any).confirmPassword = 'bad'

    await (wrapper.vm as any).handleRegister()
    expect((wrapper.vm as any).error).toBe('fail')
  })
})
