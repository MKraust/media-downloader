import { createContext, FC, useContext } from 'react'

import { AuthProvider, createService } from './base/api-wrapper'
import { ApiService } from './service'

export * from './models'

const BASE_URL = 'http://media.mkraust.ru/api/'

export const createApi = (authProvider?: AuthProvider) => {
  return {
    api: createService(ApiService, BASE_URL, authProvider),
  }
}

const ApiContext = createContext<ReturnType<typeof createApi>>(createApi())

const ApiProvider: FC = ({ children }) => {
  const value = createApi()

  return <ApiContext.Provider value={value}>{children}</ApiContext.Provider>
}

const useApi = () => {
  const { api } = useContext(ApiContext)
  return api
}

export { ApiProvider, useApi }
