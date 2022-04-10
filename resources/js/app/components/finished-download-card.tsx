import { FC, useState } from 'react'
import { Button, Card, Spinner, Table } from 'react-bootstrap'

import { IFinishedDownload } from '@/api'
import { selectTrackerById } from '@/store/trackers'
import { confirm, humanizeDatetime } from '@/helpers'
import { Icon } from '@/components/icon'
import { deleteFinishedDownload } from '@/store/downloads-history'

export interface FinishedDownloadCardProps {
  download: IFinishedDownload
}

export const FinishedDownloadCard: FC<FinishedDownloadCardProps> = ({ download }) => {
  const { id, torrent, finished_at: finishedAt, path, is_deleted: isDeleted, meta } = download
  const { media } = torrent
  const { rename_log: renameLog } = meta

  const [isExpanded, setIsExpanded] = useState(false)
  const [isDeleting, setIsDeleting] = useState(false)

  const tracker = selectTrackerById(media.tracker_id)
  const toggleExpand = () => setIsExpanded(!isExpanded)
  const handleDelete = async () => {
    if (await confirm('Вы уверены?', `Удалить загруженные файлы «${media.title}» (${torrent.name})?`)) {
      setIsDeleting(true)
      await deleteFinishedDownload(id)
      setIsDeleting(false)
    }
  }

  const renderTitle = () => (
    <div className="d-flex flex-column fs-5 text-truncate">
      <span className="text-wrap">{media.title}</span>
      <span className="text-truncate text-muted fs-6">{torrent.name}</span>
    </div>
  )

  const renderTrackerIcon = () => tracker && (
    <div className="symbol symbol-40px symbol-light">
      <span className="symbol-label">
        <img src={tracker.icon} className="h-75 w-75 alight-self-center" alt="" />
      </span>
    </div>
  )

  const renderExpandedContent = () => (
    <div className="d-flex flex-column gap-3">
      <div className="d-flex gap-2">
        <Button size="sm" variant="light-primary">
          Восстановить названия
        </Button>
      </div>
      <div className="text-wrap">{path}</div>
      <Table size="sm" responsive hover>
        <tbody>
        {renameLog.map((log) => (
          <tr key={log.from}>
            <td className="text-wrap">{log.from}</td>
            <td className="text-wrap">{log.to}</td>
          </tr>
        ))}
        </tbody>
      </Table>
    </div>
  )

  const renderButtons = () => !isDeleted && (
    <div className="d-flex gap-2">
      <Button size="sm" variant="light-info" className="btn-icon" onClick={toggleExpand}>
        <Icon name={isExpanded ? 'chevron-up' : 'chevron-down'} />
      </Button>
      <Button size="sm" variant="light-danger" className="btn-icon" onClick={handleDelete}>
        {isDeleting ? <Spinner size="sm" animation="border" /> : <Icon name="trash-alt" />}
      </Button>
    </div>
  )

  return (
    <Card className="p-4">
      <div className="d-flex flex-column gap-4">
        <div className="d-flex gap-3">
          {renderTrackerIcon()}

          <div className="d-flex flex-column gap-4 flex-grow-1">
            <div className="d-flex flex-column flex-lg-row align-items-lg-center gap-3">
              <div className="d-flex flex-column flex-grow-1">
                {renderTitle()}
              </div>

              <div className="d-flex justify-content-between text-muted fs-6">
                {humanizeDatetime(finishedAt)}
                <div className="d-lg-none">
                  {renderButtons()}
                </div>
              </div>

              <div className="d-none d-lg-block">
                {renderButtons()}
              </div>
            </div>

            {isExpanded && (
              <div className="d-none d-lg-block">
                {renderExpandedContent()}
              </div>
            )}
          </div>
        </div>
        {isExpanded && (
          <div className="d-block d-lg-none">
            {renderExpandedContent()}
          </div>
        )}
      </div>
    </Card>
  )
}
