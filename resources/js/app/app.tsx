import { Suspense } from 'react'
import { Outlet } from 'react-router-dom'

import { MasterInit, LayoutProvider, LayoutSplashScreen } from '@metronic'

export const App = () => {
  return (
    <Suspense fallback={<LayoutSplashScreen />}>
      <LayoutProvider>
        <Outlet />
        <MasterInit />
      </LayoutProvider>
    </Suspense>
  )
}
