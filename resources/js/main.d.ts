export {}

declare global {
  interface Window {
    __sharedData: {
      apiHost: string | null
    }
  }
}
