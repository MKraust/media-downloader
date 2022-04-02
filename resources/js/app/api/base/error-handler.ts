import { AxiosError } from 'axios'

function HandleErrorDecorator<T, R extends() => T, E extends Error>(handler: (error: E) => void, defaultValueConstructor: R | void) {
  return function (target: object, key: string, descriptor: PropertyDescriptor) {
    const original = descriptor.value

    descriptor.value = async function (...args: unknown[]) {
      try {
        return await original.apply(this, args)
      } catch (error: unknown) {
        handler(error as E)

        if (defaultValueConstructor) {
          return defaultValueConstructor()
        }
      }

      return null
    }

    return descriptor
  }
}

const errorNotification = (error: AxiosError) => {
  // TODO: implement notifications
}

const ServiceHandleError = <T, R extends () => T>(defaultValueConstructor?: R | void) => HandleErrorDecorator<T, R, AxiosError>(errorNotification, defaultValueConstructor)

export { HandleErrorDecorator, ServiceHandleError }
