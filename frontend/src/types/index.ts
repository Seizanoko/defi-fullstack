export interface RouteRequest {
  fromStationId: string
  toStationId: string
  analyticCode: string
}

export interface Route {
  id: string
  fromStationId: string
  toStationId: string
  analyticCode: string
  distanceKm: number
  path: string[]
  createdAt: string
}

export interface ApiError {
  code?: string
  message: string
  details?: string[]
}

export interface LoginRequest {
  username: string
  password: string
}

export interface RegisterRequest {
  username: string
  password: string
}

export interface AuthResponse {
  token: string
  user: User
}

export interface User {
  id: string
  username: string
}

export interface AnalyticDistance {
  analyticCode: string
  totalDistanceKm: number
  periodStart: string
  periodEnd: string
  group: string
}

export interface AnalyticDistanceList {
  from: string
  to: string
  groupBy: 'day' | 'month' | 'year' | 'none'
  items: AnalyticDistance[]
}

