import { FC, useState } from 'react'
import { Button, Card, FormControl, FormGroup, FormLabel, InputGroup, Spinner, Stack, Table } from 'react-bootstrap'
import { Key } from 'w3c-keys'

import { ContentType, IFinishedDownload } from '@/api'
import { selectTrackerById } from '@/store/trackers'
import { confirm, humanizeDatetime } from '@/helpers'
import { Icon } from '@/components/icon'
import {
  deleteFinishedDownload,
  renameFinishedDownloadFiles,
  revertFinishedDownloadFileNames,
} from '@/store/downloads-history'

export interface FinishedDownloadCardProps {
  download: IFinishedDownload
}

export const FinishedDownloadCard: FC<FinishedDownloadCardProps> = ({ download }) => {
  const { id, torrent, finished_at: finishedAt, path, is_deleted: isDeleted, meta } = download
  const { media, content_type: contentType } = torrent
  const { rename_log: renameLog } = meta

  const [isExpanded, setIsExpanded] = useState(false)
  const [isDeleting, setIsDeleting] = useState(false)
  const [isReverting, setIsReverting] = useState(false)
  const [newTitle, setNewTitle] = useState('')
  const [newSeason, setNewSeason] = useState('')
  const [isRenaming, setIsRenaming] = useState(false)

  const tracker = selectTrackerById(media.tracker_id)
  const toggleExpand = () => setIsExpanded(!isExpanded)

  const handleDelete = async () => {
    if (await confirm('Вы уверены?', `Удалить загруженные файлы «${media.title}» (${torrent.name})?`)) {
      setIsDeleting(true)
      await deleteFinishedDownload(id)
      setIsDeleting(false)
    }
  }

  const handleRevertRenaming = async () => {
    if (await confirm('Вы уверены?', 'Вернуть исходные названия файлов?')) {
      setIsReverting(true)
      await revertFinishedDownloadFileNames(id)
      setIsReverting(false)
    }
  }

  const handleRename = async () => {
    if (newTitle && await confirm('Вы уверены?', `Переименовать файлы загрузки в «${newTitle}»?`)) {
      setIsRenaming(true)
      await renameFinishedDownloadFiles(id, newTitle, newSeason || undefined)
      setNewTitle('')
      setIsRenaming(false)
    }
  }

  const renderTitle = () => (
    <Stack className="fs-5 text-truncate">
      <span className="text-wrap">{media.title}</span>
      <span className="text-truncate text-muted fs-6">{torrent.name}</span>
    </Stack>
  )

  const renderTrackerIcon = () => tracker && (
    <div className="symbol symbol-40px symbol-light">
      <span className="symbol-label">
        <img src={tracker.icon} className="h-75 w-75 alight-self-center" alt="" />
      </span>
    </div>
  )

  const renderExpandedContent = () => (
    <Stack gap={3}>
      {contentType !== ContentType.movie && (
        <>
          <FormGroup>
            <FormLabel>Переименование файлов</FormLabel>
            <InputGroup size="sm" className="input-group-solid">
              <FormControl
                value={newTitle}
                placeholder="Название"
                onChange={(e) => setNewTitle(e.target.value)}
              />
              <FormControl
                value={newSeason}
                placeholder="Сезон"
                type="number"
                onChange={(e) => setNewSeason(e.target.value)}
              />
              <Button size="sm" variant="active-color-primary" className="btn-bg-light" disabled={isRenaming} onClick={handleRename}>
                Переименовать
              </Button>
            </InputGroup>
          </FormGroup>

          {renameLog.length > 0 && (
            <Button size="sm" variant="active-color-primary" className="btn-bg-light" disabled={isReverting} onClick={handleRevertRenaming}>
              Восстановить названия { isReverting && <Spinner size="sm" animation="border" />}
            </Button>
          )}
        </>
      )}

      <FormGroup>
        <FormLabel>Путь</FormLabel>
        <div className="text-wrap">
          {path}
        </div>
      </FormGroup>

      <FormGroup>
        <FormLabel>Переименованные файлы</FormLabel>
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
      </FormGroup>
    </Stack>
  )

  const renderButtons = () => !isDeleted && (
    <Stack direction="horizontal" gap={2}>
      <Button size="sm" variant="light-info" className="btn-icon" onClick={toggleExpand}>
        <Icon name={isExpanded ? 'chevron-up' : 'chevron-down'} />
      </Button>
      <Button size="sm" disabled={isDeleting} variant="light-danger" className="btn-icon" onClick={handleDelete}>
        {isDeleting ? <Spinner size="sm" animation="border" /> : <Icon name="trash-alt" />}
      </Button>
    </Stack>
  )

  return (
    <Card className="p-4">
      <Stack gap={4}>
        <Stack direction="horizontal" gap={3} className="align-items-start">
          {renderTrackerIcon()}

          <Stack gap={4} className="flex-grow-1">
            <Stack gap={3} className="flex-lg-row align-items-lg-center">
              {renderTitle()}

              <Stack direction="horizontal" className="justify-content-between text-muted fs-6">
                {humanizeDatetime(finishedAt)}
                <div className="d-lg-none">
                  {renderButtons()}
                </div>
              </Stack>

              <div className="d-none d-lg-block">
                {renderButtons()}
              </div>
            </Stack>

            {isExpanded && (
              <div className="d-none d-lg-block">
                {renderExpandedContent()}
              </div>
            )}
          </Stack>
        </Stack>
        {isExpanded && (
          <div className="d-block d-lg-none">
            {renderExpandedContent()}
          </div>
        )}
      </Stack>
    </Card>
  )
}
