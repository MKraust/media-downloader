import { FC, lazy, useEffect } from 'react'
import { Routes, Route, BrowserRouter, Navigate } from 'react-router-dom'

import { App } from '@/app'
import { MasterLayout, AsideMenuItem, AsideMenuGroup } from '@metronic'
import { loadTrackers, useTrackers } from '@/store/trackers'

const DashboardPage = lazy(() => import('@/pages/dashboard'))
const SearchPage = lazy(() => import('@/pages/search'))
const MediaPage = lazy(() => import('@/pages/media'))
const FavoritesPage = lazy(() => import('@/pages/favorites'))
const DownloadsPage = lazy(() => import('@/pages/downloads'))
const DownloadsHistoryPage = lazy(() => import('@/pages/history'))

const { APP_URL } = process.env

const AppRoutes: FC = () => {
  const { trackers } = useTrackers()

  useEffect(() => {
    loadTrackers()
  }, [])

  const menuItems = (
    <>
      <AsideMenuItem to="/dashboard" title="Сводка" icon="/media/icons/duotune/layouts/lay001.svg" />
      <AsideMenuItem to="/favorites" title="Избранное" icon="/media/icons/duotune/general/star.svg" />
      <AsideMenuItem to="/downloads" title="Загрузки" icon="/media/icons/duotune/files/cloud-download.svg" />
      <AsideMenuItem to="/history" title="История загрузок" icon="/media/icons/duotune/general/gen013.svg" />

      {trackers.length > 0 && <AsideMenuGroup title="Трекеры" />}
      {trackers.map((tracker) => (
        <AsideMenuItem
          key={tracker.id}
          to={`/search/${tracker.id}`}
          title={tracker.title}
          image={tracker.icon}
        />
      ))}
    </>
  )

  return (
    <BrowserRouter basename={APP_URL}>
      <Routes>
        <Route path="/" element={<App />}>
          <Route index element={<Navigate to="/dashboard" />} />
          <Route element={<MasterLayout menu={menuItems} />}>
            <Route path="dashboard" element={<DashboardPage />} />
            <Route path="favorites" element={<FavoritesPage />} />
            <Route path="downloads" element={<DownloadsPage />} />
            <Route path="history" element={<DownloadsHistoryPage />} />
            <Route path="search/:trackerId" element={<SearchPage />} />
            <Route path="media/:mediaId" element={<MediaPage />} />
          </Route>
        </Route>
      </Routes>
    </BrowserRouter>
  )
}

export { AppRoutes }
