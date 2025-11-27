import apiClient from './api'
import type { RouteRequest, Route } from '@/types'

export const routeService = {
  async calculateRoute(request: RouteRequest): Promise<Route> {
    const response = await apiClient.post<Route>('/v1/routes', request)
    console.log('Route calculation response:', response.data)
    return response.data
  }
}