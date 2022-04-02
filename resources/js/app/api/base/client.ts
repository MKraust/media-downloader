/* eslint-disable @typescript-eslint/no-explicit-any */
import {
  AxiosInstance, AxiosRequestConfig, AxiosResponse, ResponseType,
} from 'axios'

export type QueryParamsType = Record<string | number, unknown>

export enum ContentType {
  Json = 'application/json',
  FormData = 'multipart/form-data',
  UrlEncoded = 'application/x-www-form-urlencoded',
}

export interface FullRequestParams extends Omit<AxiosRequestConfig, 'data' | 'params' | 'url' | 'responseType'> {
  path: string
  type?: ContentType
  query?: QueryParamsType
  format?: ResponseType
  body?: unknown
}

export type GetRequestParams = Omit<FullRequestParams, 'path' | 'method'>
export type PostRequestParams = Omit<FullRequestParams, 'path' | 'method'>
export type PutRequestParams = Omit<FullRequestParams, 'path' | 'method'>

export class HttpClient {
  private instance: AxiosInstance

  constructor(axios: AxiosInstance) {
    this.instance = axios
  }

  private mergeRequestParams(params1: AxiosRequestConfig): AxiosRequestConfig {
    return {
      ...this.instance.defaults,
      ...params1,
      headers: {
        ...(this.instance.defaults.headers || {}),
        ...(params1.headers || {}),
      },
    }
  }

  private createFormData(input: Record<string, unknown>): FormData {
    return Object.keys(input || {}).reduce((formData, key) => {
      const property = input[key]

      if (Array.isArray(property)) {
        property.forEach((item) => {
          formData.append(key, item as Blob | string)
        })

        return formData
      }

      let value: string | Blob | File
      if (property instanceof Blob || property instanceof File) {
        value = property
      } else if (typeof property === 'object') {
        value = JSON.stringify(property)
      } else {
        value = `${property}`
      }

      formData.append(key, value as Blob | string)

      return formData
    }, new FormData())
  }

  public request = async <T = any>({
    path,
    type,
    query,
    format = 'json',
    body,
    ...params
  }: FullRequestParams): Promise<AxiosResponse<T>> => {
    const requestParams = this.mergeRequestParams(params)

    let preparedBody = body
    if (type === ContentType.FormData && body && typeof body === 'object') {
      preparedBody = this.createFormData(body as Record<string, unknown>)
    }

    return this.instance.request({
      ...requestParams,
      headers: {
        ...(type && type !== ContentType.FormData ? { 'Content-Type': type } : {}),
        ...(requestParams.headers || {}),
      },
      params: query,
      responseType: format,
      data: preparedBody,
      url: path,
    })
  }

  public get = async <T = any>(path: FullRequestParams['path'], params: GetRequestParams = {}) => this.request<T>({ method: 'GET', path, ...params })

  public post = async <T = any>(path: FullRequestParams['path'], params: PostRequestParams = {}) => this.request<T>({ method: 'POST', path, ...params })

  public put = async <T = any>(path: FullRequestParams['path'], params: PutRequestParams = {}) => this.request<T>({ method: 'PUT', path, ...params })
}

export class Api {
  http: HttpClient

  constructor(private axios: AxiosInstance) {
    this.http = new HttpClient(axios)
  }
}
