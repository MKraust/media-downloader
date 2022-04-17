import { createService } from '@mkraust/api-base'
import type { AuthProvider } from '@mkraust/api-base'

import { ApiService } from './service'

export * from './models'

const BASE_URL = window.__sharedData.apiHost || `${location.protocol}//${location.host}`
const BASE_API_URL = `${BASE_URL}/api`

export const createApi = (authProvider?: AuthProvider) => {
  return {
    api: createService(ApiService, BASE_API_URL, authProvider),
  }
}
