import Axios, { AxiosInstance } from 'axios'

export type AuthProvider = { logout: () => void }
export type ApiConstructor<T> = new (axios: AxiosInstance) => T

function createService<T>(Api: ApiConstructor<T>, basePath: string, authProvider?: AuthProvider): T {
  const axios = Axios.create({
    timeout: 59000,
    baseURL: basePath,
  })

  axios.defaults.withCredentials = process.env.APP_ENV === 'production' || false

  axios.interceptors.response.use((response) => response, async (error) => {
    if (error.response && authProvider && error.response.status === 401) {
      authProvider.logout()
    }

    return Promise.reject(error)
  })

  return new Api(axios)
}

export { createService }
