import { ReactNode, useEffect } from 'react'

import { PageTitle } from '@metronic'
import { EmptyState, Preloader } from '@/components'
import { MediaCardsList } from '@/components/media-cards-list'
import { loadFavorites, useFavorites } from '@/store/favorites'

const FavoritesPage = () => {
  const { isLoading, favorites } = useFavorites()

  useEffect(() => {
    loadFavorites()
  }, [])

  const renderContent = (content: ReactNode) => (
    <>
      <PageTitle>Избранное</PageTitle>
      {content}
    </>
  )

  if (isLoading) {
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

export default FavoritesPage
