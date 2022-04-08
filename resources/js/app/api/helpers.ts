import { HandleErrorDecorator } from '@mkraust/api-base'
import { AxiosError } from 'axios'

const errorNotification = (error: AxiosError) => {
  // TODO: implement notifications
}

export const ServiceHandleError = <T, R extends () => T>(defaultValueConstructor?: R | void) => {
  return HandleErrorDecorator<T, R, AxiosError>(errorNotification, defaultValueConstructor)
}
