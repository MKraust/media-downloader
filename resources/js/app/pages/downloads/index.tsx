import { ReactNode, useEffect, useMemo } from 'react'
import { orderBy } from 'lodash'

import { PageTitle } from '@metronic'
import { DownloadCard, EmptyState } from '@/components'
import { useDispatch, useSelector } from '@/store'
import { deleteDownload, selectDownloads, startWatchingDownloads, stopWatchingDownloads } from '@/store/downloads'
import { IDownload } from '@/api'

const DownloadsPage = () => {
  const dispatch = useDispatch()
  const downloads = useSelector(selectDownloads)
  const sortedDownloads = useMemo(() => {
    return orderBy(downloads, ['media.tracker_id', 'media.title', 'torrent.name'], ['asc'])
  }, [downloads])

  useEffect(() => {
    dispatch(startWatchingDownloads())

    return () => {
      dispatch(stopWatchingDownloads())
    }
  }, [])

  const handleDelete = (download: IDownload) => dispatch(deleteDownload(download))

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
