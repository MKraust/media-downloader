import { ReactNode, useState } from 'react'
import { useParams } from 'react-router'
import { FormControl } from 'react-bootstrap'
import { Key } from 'w3c-keys'

import { useTrackers } from '@/contexts'
import { Alert, MediaCardsList, Preloader } from '@/components'
import { PageTitle } from '@metronic'
import { IMedia, useApi } from '@/api'

const TrackerSearchPage = () => {
  const { trackerId } = useParams()
  const trackers = useTrackers()
  const api = useApi()
  const [isLoading, setLoading] = useState(false)
  const [error, setError] = useState(false)
  const [lastQuery, setLastQuery] = useState('')
  const [searchQuery, setSearchQuery] = useState('')
  const [isLastSearchEmpty, setLastSearchEmpty] = useState(false)
  const [searchResults, setSearchResults] = useState<IMedia[]>(() => [])

  const currentTracker = trackers.find((i) => i.id === trackerId)

  console.log(trackers, currentTracker, trackerId)
  if (!currentTracker) {
    return (
      <>
        <PageTitle>Загрузка...</PageTitle>
        <Preloader />
      </>
    )
  }

  const fetchSearchResults = async () => {
    if (!searchQuery) {
      return
    }

    setLastSearchEmpty(false)
    setError(false)

    try {
      setLoading(true)

      const mediaItems = await api.search(currentTracker.id, searchQuery)
      setSearchResults(mediaItems)

      setLastQuery(searchQuery)
      if (mediaItems.length === 0) {
        setLastSearchEmpty(true)
      }
    } catch (e) {
      setError(true)
    } finally {
      setLoading(false)
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
          onBlur={() => fetchSearchResults()}
          onKeyUp={(e) => {
            if (e.key === Key.Enter) {
              fetchSearchResults()
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
      <Alert
        variant="danger"
        icon="exclamation-triangle"
        title="Произошла ошибка"
        text={<>Подробности в <strong><a href={sentryLink} className="text-danger text-hover-danger">Sentry</a></strong></>}
      />
    ))
  }

  if (isLastSearchEmpty) {
    return renderContent((
      <Alert
        variant="primary"
        icon="exclamation-circle"
        text={<>По запросу <strong>{lastQuery}</strong> ничего не найдено</>}
      />
    ))
  }

  return renderContent(<MediaCardsList mediaList={searchResults} />)
}

export default TrackerSearchPage
