import '@metronic/assets/sass/style.scss'
import '@metronic/assets/sass/style.react.scss'

import { createRoot } from 'react-dom/client'
import { Provider } from 'react-redux'

import { AppRoutes } from '@/routing'
import { ApiProvider } from '@/api'
import { store } from '@/store'

createRoot(document.getElementById('root') as HTMLElement)
  .render((
    <ApiProvider>
      <Provider store={store}>
        <AppRoutes />
      </Provider>
    </ApiProvider>
  ))
