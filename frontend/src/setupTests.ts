/// <reference types="node" />

// Vitest setup file — configures the jsdom environment for tests

// Provide a minimal matchMedia stub used by Vuetify and other UI libs
Object.defineProperty(window, 'matchMedia', {
  writable: true,
  value: (query: string) => ({
    matches: false,
    media: query,
    onchange: null,
    addListener: () => {},
    removeListener: () => {},
    addEventListener: () => {},
    removeEventListener: () => {},
    dispatchEvent: () => false,
  }),
})

// Minimal ResizeObserver mock (some components may use it)
class ResizeObserverMock {
  observe () {}
  unobserve () {}
  disconnect () {}
}

global.ResizeObserver = global.ResizeObserver || ResizeObserverMock

// Clear localStorage between tests by default
import { beforeEach } from 'vitest'
beforeEach(() => {
  localStorage.clear()
})
