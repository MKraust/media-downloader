import { createContext, FC, useContext } from 'react'
import { createService } from '@mkraust/api-base'
import type { AuthProvider } from '@mkraust/api-base'

import { ApiService } from './service'

import { PropsWithOnlyChildren } from '@/helpers'

export * from './models'

const BASE_URL = window.__sharedData.apiHost || `${location.protocol}//${location.host}`
const BASE_API_URL = `${BASE_URL}/api`

export const createApi = (authProvider?: AuthProvider) => {
  return {
    api: createService(ApiService, BASE_API_URL, authProvider),
  }
}

const ApiContext = createContext<ReturnType<typeof createApi>>(createApi())

const ApiProvider: FC<PropsWithOnlyChildren> = ({ children }) => {
  const value = createApi()

  return <ApiContext.Provider value={value}>{children}</ApiContext.Provider>
}

const useApi = () => {
  const { api } = useContext(ApiContext)
  return api
}

export { ApiProvider, useApi }
