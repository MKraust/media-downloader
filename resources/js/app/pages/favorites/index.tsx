import { ReactNode, useEffect } from 'react'

import { PageTitle } from '@metronic'
import { EmptyState, Preloader } from '@/components'
import { MediaCardsList } from '@/components/media-cards-list'
import { useDispatch, useSelector } from '@/store'
import { loadFavorites, selectFavorites, selectIsLoadingFavorites } from '@/store/favorites'

export const FavoritesPage = () => {
  const dispatch = useDispatch()
  const isLoadingFavorites = useSelector(selectIsLoadingFavorites)
  const favorites = useSelector(selectFavorites)

  useEffect(() => {
    dispatch(loadFavorites())
  }, [])

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
