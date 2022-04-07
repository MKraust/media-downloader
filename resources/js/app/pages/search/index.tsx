import { ReactNode, useEffect, useState } from 'react'
import { useParams } from 'react-router'
import { FormControl } from 'react-bootstrap'
import { Key } from 'w3c-keys'

import { EmptyState, MediaCardsList, Preloader } from '@/components'
import { PageTitle } from '@metronic'
import { selectTrackerById } from '@/store/trackers'
import { searchMedia, useSearch } from '@/store/search'

const SearchPage = () => {
  const { trackerId } = useParams()

  const { isLoading, error, lastSearchIsEmpty, lastSearchQuery, items } = useSearch(trackerId)

  const [searchQuery, setSearchQuery] = useState('')

  const currentTracker = selectTrackerById(trackerId)

  useEffect(() => {
    setSearchQuery(lastSearchQuery)
  }, [trackerId])

  if (!currentTracker) {
    return (
      <>
        <PageTitle>Загрузка...</PageTitle>
        <Preloader />
      </>
    )
  }

  const handleSearch = () => {
    if (trackerId) {
      searchMedia(trackerId, searchQuery)
    }
  }

  const renderContent = (content: ReactNode) => (
    <>
      <PageTitle>Поиск по {currentTracker.title}</PageTitle>
      <div className="d-flex flex-column gap-4">
        <FormControl
          value={searchQuery}
          placeholder="Поиск"
          disabled={isLoading}
          onChange={(e) => setSearchQuery(e.target.value)}
          onKeyUp={(e) => {
            if (e.key === Key.Enter) {
              handleSearch()
            }
          }}
        />

        {content}
      </div>
    </>
  )

  if (isLoading) {
    return renderContent(<Preloader />)
  }

  if (error) {
    const sentryLink = 'https://sentry.io/organizations/personal-purposes/issues/?project=5284009'

    return renderContent((
      <EmptyState variant="danger" icon="exclamation-triangle">
        Произошла ошибка. Подробности в <strong><a href={sentryLink}>Sentry</a></strong>
      </EmptyState>
    ))
  }

  if (lastSearchIsEmpty) {
    return renderContent((
      <EmptyState variant="danger" icon="exclamation-triangle">
        По запросу <strong>{lastSearchQuery}</strong> ничего не найдено
      </EmptyState>
    ))
  }

  return renderContent(<MediaCardsList mediaList={items} />)
}

export default SearchPage
