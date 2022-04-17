import '@metronic/assets/sass/style.scss'
import '@metronic/assets/sass/style.react.scss'

import { createRoot } from 'react-dom/client'
import { Provider } from 'react-redux'

import { AppRoutes } from '@/routing'
import { store } from '@/store'

createRoot(document.getElementById('root') as HTMLElement)
  .render((
    <Provider store={store}>
      <AppRoutes />
    </Provider>
  ))
