import { describe, it, expect, vi, beforeEach } from 'vitest'
import { shallowMount } from '@vue/test-utils'
import { V_STUBS } from '../test-utils'

vi.mock('@/services/routeService', () => ({
  routeService: { calculateRoute: vi.fn() },
}))

import RouteCalculator from '@/components/RouteCalculator.vue'
import { routeService } from '@/services/routeService'

const GLOBAL_STUBS = ['v-card','v-form','v-text-field','v-btn','v-alert']

beforeEach(() => {
  vi.restoreAllMocks()
})

// RouteCalculator component tests
describe('RouteCalculator', () => {
  it('calls service and emits route-calculated on success', async () => {
    const fakeRoute = { id: 'r1', fromStationId: 'A', toStationId: 'B', analyticCode: 'c', distanceKm: 1, path: ['A','B'], createdAt: new Date().toISOString() }
    ;(routeService.calculateRoute as any).mockResolvedValue(fakeRoute)

    const wrapper = shallowMount(RouteCalculator, { global: { stubs: V_STUBS } })

    ;(wrapper.vm as any).form.fromStationId = 'A'
    ;(wrapper.vm as any).form.toStationId = 'B'
    ;(wrapper.vm as any).form.analyticCode = 'c'
    ;(wrapper.vm as any).valid = true

    await (wrapper.vm as any).calculateRoute()

    expect(routeService.calculateRoute).toHaveBeenCalledWith({ fromStationId: 'A', toStationId: 'B', analyticCode: 'c' })
    const emitted = wrapper.emitted('route-calculated')
    expect(emitted).toBeTruthy()
    expect(emitted?.[0]?.[0]).toEqual(fakeRoute)
  })

  it('sets error message when service throws', async () => {
    ;(routeService.calculateRoute as any).mockRejectedValue({ message: 'not found' })

    const wrapper = shallowMount(RouteCalculator, { global: { stubs: V_STUBS } })
    ;(wrapper.vm as any).valid = true
    ;(wrapper.vm as any).form.fromStationId = 'A'
    ;(wrapper.vm as any).form.toStationId = 'B'
    ;(wrapper.vm as any).form.analyticCode = 'c'

    await (wrapper.vm as any).calculateRoute()
    expect((wrapper.vm as any).error).toBe('not found')
  })
})
