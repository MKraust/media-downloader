export * from './popup'
export * from './notifications'

export const humanizeBytes = (bytes: number, fractionDigits: number) => {
  const thresh = 1024

  if (Math.abs(bytes) < thresh) {
    return bytes + ' Б'
  }

  const units = ['кБ', 'МБ', 'ГБ', 'ТБ']
  let u = -1
  const r = 10 ** fractionDigits

  let preparedBytes = bytes

  do {
    preparedBytes /= thresh
    ++u
  } while (Math.round(Math.abs(preparedBytes) * r) / r >= thresh && u < units.length - 1)

  return preparedBytes.toFixed(fractionDigits) + ' ' + units[u]
}

export const humanizeEstimate = (seconds: number) => {
  const eta = seconds

  if (eta < 60) {
    return `${eta} сек`
  }

  if (eta < 3600) {
    const minutes = Math.floor(eta / 60)
    return `${minutes} мин`
  }

  if (eta < 3600 * 24) {
    const hours = Math.floor(eta / 3600)
    const minutes = Math.floor((eta % 3600) / 60)
    return `${hours} ч ${minutes} мин`
  }

  const days = Math.floor(eta / 3600 * 24)
  if (days > 5) {
    return '∞'
  }

  return `${days} дн`
}

export const minMaxWidth = (px: number) => ({
  minWidth: `${px}px`,
  maxWidth: `${px}px`,
})
