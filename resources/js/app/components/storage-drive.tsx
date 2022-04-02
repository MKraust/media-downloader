import { FC, useMemo } from 'react'
import { ProgressBar } from 'react-bootstrap'

import { IStorageDrive } from '@/api'
import { humanizeBytes, minMaxWidth } from '@/helpers'
import { Icon } from '@/components/icon'

const formatSize = (val: number) => humanizeBytes(val, 1)

export interface StorageDriveProps {
  value: IStorageDrive
}

export const StorageDrive: FC<StorageDriveProps> = ({ value }) => {
  const { name, total, used, available, usage_percent: usagePercent } = value

  const formattedTotal = useMemo(() => formatSize(total), [total])
  const formattedAvailable = useMemo(() => formatSize(available), [available])
  const formattedUsed = useMemo(() => formatSize(used), [used])

  const variant = useMemo(() => {
    if (usagePercent >= 85) {
      return 'danger'
    }

    return usagePercent >= 50 ? 'warning' : 'success'
  }, [usagePercent])

  return (
    <div className="d-flex align-items-center gap-4">
      <div className="symbol symbol-50px symbol-light">
        <span className="symbol-label">
          <Icon name="hdd" style="duo" size="1" className={`align-self-center text-${variant}`} />
        </span>
      </div>

      <div className="d-flex flex-column">
        <span className="h5">{name}</span>
        <span className="fs-6 text-muted">{formattedAvailable} свободно</span>
      </div>
    </div>
  )
}
