import { useParams } from 'react-router'
import { useEffect, useState } from 'react'
import { Button, Card, Col, FormSelect, Row } from 'react-bootstrap'
import clsx from 'clsx'
import { orderBy } from 'lodash'

import { PageDescription, PageTitle } from '@metronic'
import { Preloader, TorrentCard, Icon, EmptyState } from '@/components'
import { confirm, notifySuccess } from '@/helpers'
import { ITorrent, useApi } from '@/api'
import { useDispatch, useSelector } from '@/store'
import { loadMedia, selectError, selectIsLoadingMedia, selectMedia } from '@/store/media'

const useSort = () => {
  const [sortBy, setSortBy] = useState('size_int')
  const [sortingOrder, setSortingOrder] = useState<'asc' | 'desc'>('desc')

  const switchSortingOrder = () => setSortingOrder(sortingOrder === 'asc' ? 'desc' : 'asc')
  const sortingOrderIcon = sortingOrder === 'asc' ? 'sort-amount-down-alt' : 'sort-amount-down'

  return {
    sortBy,
    setSortBy,
    switchSortingOrder,
    sortingOrderIcon,
    sortingOrder,
  }
}

const TrackerMediaPage = () => {
  const api = useApi()
  const dispatch = useDispatch()

  const { mediaId } = useParams()

  const isLoading = useSelector(selectIsLoadingMedia)
  const media = useSelector(selectMedia)
  const error = useSelector(selectError)

  useEffect(() => {
    if (mediaId && mediaId !== media?.id) {
      dispatch(loadMedia(mediaId))
    }
  }, [])

  const { switchSortingOrder, sortBy, setSortBy, sortingOrder, sortingOrderIcon } = useSort()
  const isSomeSeriesTorrents = media ? media.torrents.some((torrent) => torrent.content_type === 'series') : false
  const [isTogglingFavorite, setTogglingFavorite] = useState(false)

  if (isLoading) {
    return (
      <>
        <PageTitle>Загрузка...</PageTitle>
        <Preloader />
      </>
    )
  }

  if (error) {
    const sentryLink = 'https://sentry.io/organizations/personal-purposes/issues/?project=5284009'

    return (
      <>
        <PageTitle>Ошибка</PageTitle>
        <EmptyState variant="danger" icon="exclamation-triangle">
          Произошла ошибка. Подробности в <strong><a href={sentryLink}>Sentry</a></strong>
        </EmptyState>
      </>
    )
  }

  if (!media) {
    return (
      <>
        <PageTitle>Ошибка</PageTitle>
        <div className="d-flex justify-content-center">
          Медиа контент не найден
        </div>
      </>
    )
  }

  const sortedTorrents = (() => {
    if (sortBy === 'size_int') {
      return orderBy(media.torrents, ['size_int'], [sortingOrder])
    }

    if (sortBy === 'season') {
      return orderBy(media.torrents, ['season', 0], [sortingOrder])
    }

    return media.torrents
  })()

  const openMediaPageInTracker = () => {
    window.open(media.url)
  }

  const toggleFavorite = async () => {
    try {
      setTogglingFavorite(true)
      if (media.is_favorite) {
        await api.removeFromFavorites(media.id)
        media.is_favorite = false
      } else {
        await api.addToFavorites(media.id)
        media.is_favorite = true
      }
    } finally {
      setTogglingFavorite(false)
    }
  }

  const handleDownload = async (torrent: ITorrent) => {
    if (await confirm('Скачать?', torrent.name)) {
      await api.startDownload(torrent.id)
      notifySuccess(torrent.name, 'Загрузка началась')
    }
  }

  return (
    <>
      <PageTitle>{media.title}</PageTitle>
      {media.original_title && (
        <PageDescription>{media.original_title}</PageDescription>
      )}
      <Row>
        <Col md={4} className="mb-4">
          <div className="card ribbon ribbon-start mb-4">
            {media.series_count && (
              <div className="ribbon-label bg-danger" style={{ top: '24px' }}>
                {media.series_count}
              </div>
            )}
            <Card.Img src={media.poster} className="w-100" />
          </div>
          <div className="d-flex gap-2">
            <Button
              size="lg"
              variant={media.is_favorite ? 'warning' : 'info'}
              className={clsx('w-100 btn-icon', isTogglingFavorite && 'spinner spinner-white spinner-right')}
              disabled={isTogglingFavorite}
              onClick={toggleFavorite}
            >
              <Icon name="star" size="2" style={media.is_favorite ? 'solid' : 'light'} />
            </Button>
            <Button
              size="lg"
              variant="info"
              className="w-100 btn-icon"
              onClick={openMediaPageInTracker}
            >
              <Icon name="external-link-square" size="2" />
            </Button>
          </div>
        </Col>
        <Col md={8}>
          <div className="input-group mb-4">
            <FormSelect value={sortBy} onChange={(e) => setSortBy(e.target.value)}>
              <option value="size_int">По размеру</option>
              {isSomeSeriesTorrents && (
                <option value="season">По сезону</option>
              )}
            </FormSelect>
            <div className="input-group-append">
              <Button onClick={switchSortingOrder} className="btn-icon">
                <Icon name={sortingOrderIcon} size="2" />
              </Button>
            </div>
          </div>
          {sortedTorrents.map((torrent) => (
            <div key={torrent.id} className="mb-3">
              <TorrentCard value={torrent} onClick={() => handleDownload(torrent)} />
            </div>
          ))}
        </Col>
      </Row>
    </>
  )
}

export default TrackerMediaPage
