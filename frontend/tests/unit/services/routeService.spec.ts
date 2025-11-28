import { beforeEach, describe, expect, it, vi } from 'vitest'
import apiClient from '@/services/api'
import { routeService } from '@/services/routeService'

beforeEach(() => {
  vi.restoreAllMocks()
})

// routeService tests
describe('routeService', () => {
  it('calculateRoute calls api and returns route data', async () => {
    const fakeRoute = { id: 'R1', fromStationId: 'A', toStationId: 'B', analyticCode: 'X', distanceKm: 10, path: ['A', 'B'], createdAt: new Date().toISOString() }
    vi.spyOn(apiClient, 'post').mockResolvedValueOnce({ data: fakeRoute } as any)

    const result = await routeService.calculateRoute({ fromStationId: 'A', toStationId: 'B', analyticCode: 'X' })
    expect(result).toEqual(fakeRoute)
    expect(apiClient.post).toHaveBeenCalledWith('/v1/routes', expect.any(Object))
  })
})
