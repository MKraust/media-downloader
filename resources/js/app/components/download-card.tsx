import { Button, Card, FormCheck, ProgressBar } from 'react-bootstrap'
import { CSSProperties, FC, useMemo, useState } from 'react'
import clsx from 'clsx'

import { IDownload, useApi } from '@/api'
import { useTrackers } from '@/contexts'
import { humanizeBytes, humanizeEstimate, minMaxWidth } from '@/helpers'
import { Icon } from '@/components/icon'

const progressStatuses: IDownload['state_original'][] = ['downloading', 'uploading', 'metaDL', 'checkingDL', 'checkingUP']

export interface DownloadCardProps {
  download: IDownload
  onDelete: () => void
}

export const DownloadCard: FC<DownloadCardProps> = ({ download, onDelete }) => {
  const {
    media,
    torrent,
    hash,
    progress,
    state_original: stateOriginal,
    download_speed_in_bytes_per_second: downloadSpeedInBytesPerSecond,
    estimate_in_seconds: estimateInSeconds,
  } = download

  const trackers = useTrackers()
  const api = useApi()
  const [isChangingState, setChangingState] = useState(false)
  const isMovie = useMemo(() => torrent.content_type === 'movie', [torrent.content_type])
  const isInProgress = useMemo(() => progressStatuses.includes(stateOriginal), [stateOriginal])
  const progressPercent = useMemo(() => Number(progress) * 100, [progress])
  const speed = useMemo(() => humanizeBytes(downloadSpeedInBytesPerSecond, 1) + '/c', [downloadSpeedInBytesPerSecond])
  const estimate = useMemo(() => humanizeEstimate(estimateInSeconds), [estimateInSeconds])
  const statusColor = useMemo(() => {
    switch (stateOriginal) {
      case 'error':
        return 'danger'

      case 'stalledUP':
      case 'stalledDL':
        return 'warning'

      default:
        return 'primary'
    }
  }, [stateOriginal])

  const isActive = useMemo(() => !['error', 'pausedUP', 'pausedDL'].includes(stateOriginal), [stateOriginal])

  const setActive = async (val: boolean) => {
    setChangingState(true)

    download.state_original = 'pausedDL'

    if (val) {
      await api.resumeDownload(hash)
    } else {
      await api.pauseDownload(hash)
    }

    setChangingState(false)
  }

  const tracker = trackers.find((tracker) => tracker.id === media.tracker_id)

  const renderSwitch = () => (
    <FormCheck
      type="switch"
      disabled={isChangingState}
      checked={isActive}
      className="form-check form-check-custom form-check-solid"
      onChange={(e) => setActive(e.target.checked)}
    />
  )

  const renderDeleteButton = () => (
    <Button variant="danger" size="sm" className="btn-icon btn-delete" onClick={onDelete}>
      <Icon name="trash-alt" size="2" />
    </Button>
  )

  const renderProgressBar = (className?: string, style?: CSSProperties) => (
    <div className={clsx('align-self-stretch', className)} style={style}>
      <ProgressBar
        now={progressPercent}
        variant={statusColor}
        animated={isInProgress}
        className={'h-100'}
      />
    </div>
  )

  const renderAttributes = () => {
    const iconSize = 3

    return (
      <>
        <div className="d-flex align-items-center gap-2">
          <Icon name="forward" size={iconSize} style="duo" className="text-primary" />
          <span>{ speed }</span>
        </div>

        <div className="d-flex align-items-center gap-2">
          <Icon name="stopwatch" size={iconSize} style="duo" className="text-primary" />
          <span>{ estimate }</span>
        </div>
      </>
    )
  }

  return (
    <Card className="bg-white p-4 shadow-sm">
      <div className="d-none d-xl-flex align-items-center gap-3">
        {renderSwitch()}

        {tracker && (
          <div className="symbol symbol-30px symbol-light">
            <span className="symbol-label">
              <img src={tracker.icon} className="h-75 w-75 alight-self-center" alt="" />
            </span>
          </div>
        )}

        <span className="flex-grow-1 fs-5 text-truncate d-flex align-items-center gap-2">
          <span>{media.title}</span>
          {!isMovie && <span className="text-muted fs-6">{torrent.name}</span>}
        </span>

        <div className="d-flex align-items-center gap-5 mx-5">
          {renderAttributes()}
        </div>

        {renderProgressBar('', minMaxWidth(250))}
        {renderDeleteButton()}
      </div>

      <div className="d-block d-xl-none">
        <div className="text-truncate fs-5 mb-1">
          { media.title }
        </div>
        {!isMovie && (
          <div className="text-truncate fs-6 text-muted mb-3">
            { torrent.name }
          </div>
        )}

        <div className="d-flex gap-3 mb-3">
          {renderAttributes()}
        </div>

        <div className="d-flex align-items-center gap-2">
          {renderSwitch()}
          {renderProgressBar('flex-grow-1')}
          {renderDeleteButton()}
        </div>
      </div>
    </Card>
  )
}
