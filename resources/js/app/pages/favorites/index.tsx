import { ReactNode, useEffect, useState } from 'react'
import { Alert } from 'react-bootstrap'

import { PageTitle } from '@metronic'
import { IMedia, useApi } from '@/api'
import { EmptyState, Preloader } from '@/components'
import { MediaCardsList } from '@/components/media-cards-list'

const useFavorites = () => {
  const api = useApi()
  const [isLoadingFavorites, setLoadingFavorites] = useState(false)
  const [favorites, setFavorites] = useState<IMedia[]>(() => [])

  useEffect(() => {
    const fetchFavorites = async () => {
      setLoadingFavorites(true)
      setFavorites(await api.loadFavorites())
      setLoadingFavorites(false)
    }

    fetchFavorites()
  }, [])

  return { isLoadingFavorites, favorites }
}

export const FavoritesPage = () => {
  const { isLoadingFavorites, favorites } = useFavorites()

  const renderContent = (content: ReactNode) => (
    <>
      <PageTitle>Избранное</PageTitle>
      {content}
    </>
  )

  if (isLoadingFavorites) {
    return renderContent(<Preloader />)
  }

  if (favorites.length === 0) {
    return renderContent((
      <EmptyState icon="exclamation-circle" iconStyle={'duo'}>
        В избранном пока ничего нет
      </EmptyState>
    ))
  }

  return renderContent(<MediaCardsList mediaList={favorites} />)
}
