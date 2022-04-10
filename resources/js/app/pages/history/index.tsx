import { ReactNode, useEffect } from 'react'

import { PageTitle } from '@metronic'
import { EmptyState, Preloader } from '@/components'
import { loadFinishedDownloads, useDownloadsHistory } from '@/store/downloads-history'
import { FinishedDownloadCard } from '@/components/finished-download-card'

const DownloadsHistoryPage = () => {
  const { isLoading, downloads } = useDownloadsHistory()

  useEffect(() => {
    loadFinishedDownloads()
  }, [])

  const renderContent = (content: ReactNode) => (
    <>
      <PageTitle>История загрузок</PageTitle>
      {content}
    </>
  )

  if (isLoading) {
    return renderContent(<Preloader />)
  }

  if (downloads.length === 0) {
    return renderContent((
      <EmptyState icon="exclamation-circle" iconStyle={'duo'}>
        Пока что ничего не загружалось
      </EmptyState>
    ))
  }

  return renderContent((
    <div className="d-flex flex-column gap-3">
      {downloads.map((download) => (
        <FinishedDownloadCard key={download.id} download={download} />
      ))}
    </div>
  ))
}

export default DownloadsHistoryPage
