import { FC, lazy, useEffect } from 'react'
import { Routes, Route, BrowserRouter, Navigate } from 'react-router-dom'

import { App } from '@/app'
import { MasterLayout, AsideMenuItem, AsideMenuGroup } from '@metronic'
import { FavoritesPage } from '@/pages/favorites'
import { useDispatch, useSelector } from '@/store'
import { loadTrackers, selectTrackers } from '@/store/trackers'

const DashboardPage = lazy(() => import('@/pages/dashboard'))
const TrackerSearchPage = lazy(() => import('@/pages/tracker/search'))
const TrackerMediaPage = lazy(() => import('@/pages/tracker/media'))
const DownloadsPage = lazy(() => import('@/pages/downloads'))

const { APP_URL } = process.env

const AppRoutes: FC = () => {
  const trackers = useSelector(selectTrackers)
  const dispatch = useDispatch()

  useEffect(() => {
    dispatch(loadTrackers())
  }, [])

  const menuItems = (
    <>
      <AsideMenuItem to="/dashboard" title="Сводка" icon="/media/icons/duotune/layouts/lay001.svg" />
      <AsideMenuItem to="/favorites" title="Избранное" icon="/media/icons/duotune/general/star.svg" />
      <AsideMenuItem to="/downloads" title="Загрузки" icon="/media/icons/duotune/files/cloud-download.svg" />

      {trackers.length > 0 && <AsideMenuGroup title="Трекеры" />}
      {trackers.map((tracker) => (
        <AsideMenuItem
          key={tracker.id}
          to={`/${tracker.id}`}
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
            <Route path=":trackerId">
              <Route index element={<TrackerSearchPage />} />
              <Route path={':id'} element={<TrackerMediaPage />} />
            </Route>
          </Route>
        </Route>
      </Routes>
    </BrowserRouter>
  )
}

export { AppRoutes }
