import { ReactNode, useEffect, useState } from 'react'
import { useParams } from 'react-router'
import { FormControl } from 'react-bootstrap'
import { Key } from 'w3c-keys'

import { EmptyState, MediaCardsList, Preloader } from '@/components'
import { PageTitle } from '@metronic'
import { useDispatch, useSelector } from '@/store'
import { selectTrackerById } from '@/store/trackers'
import {
  searchMedia,
  selectError, selectFoundMedia,
  selectIsLastSearchEmpty,
  selectIsLoadingSearchResults,
  selectLastSearchQuery,
} from '@/store/search'

const SearchPage = () => {
  const { trackerId } = useParams()
  const dispatch = useDispatch()

  const currentTracker = useSelector(selectTrackerById(trackerId))
  const isLoading = useSelector(selectIsLoadingSearchResults(trackerId))
  const error = useSelector(selectError(trackerId))
  const isLastSearchEmpty = useSelector(selectIsLastSearchEmpty(trackerId))
  const lastQuery = useSelector(selectLastSearchQuery(trackerId))
  const searchResults = useSelector(selectFoundMedia(trackerId))

  const [searchQuery, setSearchQuery] = useState('')

  useEffect(() => {
    setSearchQuery(lastQuery)
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
      dispatch(searchMedia(trackerId, searchQuery))
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

  if (isLastSearchEmpty) {
    return renderContent((
      <EmptyState variant="danger" icon="exclamation-triangle">
        По запросу <strong>{lastQuery}</strong> ничего не найдено
      </EmptyState>
    ))
  }

  return renderContent(<MediaCardsList mediaList={searchResults} />)
}

export default SearchPage
