class AsyncInterval {

  private readonly callback: () => Promise<void> = async () => {}

  private readonly interval: number = 1000

  private isRunning = false

  constructor(callback: () => Promise<void>, interval: number) {
    this.callback = callback
    this.interval = interval
  }

  public start() {
    this.isRunning = true
    this.runAsyncInterval()
  }

  public stop() {
    this.isRunning = false
  }

  private async runAsyncInterval() {
    await this.callback()

    if (this.isRunning) {
      setTimeout(() => this.runAsyncInterval(), this.interval)
    }
  }
}

export const runAsyncInterval = (...args: ConstructorParameters<typeof AsyncInterval>) => {
  const asyncInterval = new AsyncInterval(...args)
  asyncInterval.start()

  return asyncInterval
}
