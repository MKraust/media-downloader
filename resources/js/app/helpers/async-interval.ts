export class AsyncInterval {

  private readonly callback: () => Promise<void> = async () => {}

  private readonly interval: number = 1000

  private _isRunning = false

  constructor(callback: () => Promise<void>, interval: number) {
    this.callback = callback
    this.interval = interval
  }

  public isRunning() {
    return this.isRunning
  }

  public start() {
    if (this._isRunning) {
      return
    }

    this._isRunning = true
    this.runAsyncInterval()
  }

  public stop() {
    this._isRunning = false
  }

  private async runAsyncInterval() {
    await this.callback()

    if (this._isRunning) {
      setTimeout(() => this.runAsyncInterval(), this.interval)
    }
  }
}

export const runAsyncInterval = (...args: ConstructorParameters<typeof AsyncInterval>) => {
  const asyncInterval = new AsyncInterval(...args)
  asyncInterval.start()

  return asyncInterval
}
