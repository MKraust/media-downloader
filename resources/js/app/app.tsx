import { Suspense } from 'react'
import { Outlet } from 'react-router-dom'
import { Provider } from 'react-redux'

import { MasterInit, LayoutProvider, LayoutSplashScreen } from '@metronic'
import { store } from '@/store'

export const App = () => {
  return (
    <Suspense fallback={<LayoutSplashScreen />}>
      <Provider store={store}>
        <LayoutProvider>
          <Outlet />
          <MasterInit />
        </LayoutProvider>
      </Provider>
    </Suspense>
  )
}
