class AsyncInterval {

  private callback: () => Promise<void> = async () => {}

  private interval = 1000

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

export const runAsyncInterval = (callback: () => Promise<void>, interval: number) => {
  const asyncInterval = new AsyncInterval(callback, interval)
  asyncInterval.start()

  return asyncInterval
}
