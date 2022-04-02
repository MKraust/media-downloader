import { ReactNode, useEffect, useMemo, useState } from 'react'
import { orderBy } from 'lodash'

import { PageTitle } from '@metronic'
import { DownloadCard, EmptyState } from '@/components'
import { IDownload, useApi } from '@/api'
import { confirm, runAsyncInterval } from '@/helpers'

const useDownloads = () => {
  const api = useApi()
  const [downloads, setDownloads] = useState<IDownload[]>(() => [])

  const refreshDownloads = async () => {
    setDownloads(await api.loadDownloads())
  }

  useEffect(() => {
    const asyncInterval = runAsyncInterval(refreshDownloads, 1000)

    return () => {
      asyncInterval.stop()
    }
  }, [])

  const handleDelete = async ({ torrent, media, hash }: IDownload) => {
    const downloadName = media.title + (torrent.content_type !== 'movie' ? ` ${torrent.name}` : '')

    if (await confirm('Удалить загрузку?', downloadName)) {
      await api.deleteDownload(hash)
      setDownloads(downloads.filter((download) => download.hash !== hash))
    }
  }

  return { downloads, handleDelete }
}

const DownloadsPage = () => {
  const { downloads, handleDelete } = useDownloads()
  const sortedDownloads = useMemo(() => orderBy(downloads, ['media.tracker_id', 'media.title', 'torrent.name'], ['asc']), [downloads])

  const renderContent = (content: ReactNode) => (
    <>
      <PageTitle>Загрузки</PageTitle>
      {content}
    </>
  )

  if (downloads.length === 0) {
    return renderContent((
      <EmptyState icon="exclamation-circle" iconStyle={'duo'}>
        В данный момент нет активных загрузок
      </EmptyState>
    ))
  }

  return renderContent((
    <div className="d-flex flex-column gap-3">
      {sortedDownloads.map((download) => (
        <DownloadCard
          key={download.hash}
          download={download}
          onDelete={() => handleDelete(download)}
        />
      ))}
    </div>
  ))
}

export default DownloadsPage
