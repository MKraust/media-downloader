export function humanizeBytes(bytes, fractionDigits) {
  const thresh = 1024;

  if (Math.abs(bytes) < thresh) {
    return bytes + ' Б';
  }

  const units = ['кБ', 'МБ', 'ГБ', 'ТБ'];
  let u = -1;
  const r = 10 ** fractionDigits;

  do {
    bytes /= thresh;
    ++u;
  } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


  return bytes.toFixed(fractionDigits) + ' ' + units[u];
}