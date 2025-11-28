import type { AnalyticDistanceList } from '@/types'
import apiClient from './api'

export const statsService = {
  async stats (from?: string, to?: string, groupBy?: string): Promise<AnalyticDistanceList> {
    const response = await apiClient.get<AnalyticDistanceList>('/v1/stats/distances', { params: { from, to, groupBy } })
    console.log('Stats response:', response.data)
    return response.data
  },
}
