import { describe, it, expect } from 'vitest'
import { shallowMount } from '@vue/test-utils'
import { V_STUBS } from '../test-utils'
import RouteResult from '@/components/RouteResult.vue'

const GLOBAL_STUBS = ['v-card','v-list','v-list-item','v-list-item-title','v-list-item-subtitle','v-chip-group','v-chip']

// RouteResult component tests
describe('RouteResult', () => {
  it('displays route properties correctly', () => {
    const route = { id: 'r1', fromStationId: 'A', toStationId: 'B', analyticCode: 'c', distanceKm: 3.5, path: ['A','C','B'], createdAt: new Date('2025-01-01T00:00:00Z').toISOString() }
    const wrapper = shallowMount(RouteResult, { props: { route }, global: { stubs: V_STUBS } })

    expect(wrapper.html()).toContain('Route ID')
    expect(wrapper.html()).toContain('r1')
    expect(wrapper.html()).toContain('From Station')
    expect(wrapper.html()).toContain('A')
    expect(wrapper.html()).toContain('Distance')
    expect(wrapper.html()).toContain('3.5 km')
    expect(wrapper.html()).toContain('Created At')
  })
})
