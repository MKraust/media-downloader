import '@metronic/assets/sass/style.scss'
import '@metronic/assets/sass/style.react.scss'

import { AppRoutes } from '@/routing'
import { createRoot } from 'react-dom/client'
import { ApiProvider } from '@/api'

createRoot(document.getElementById('root') as HTMLElement)
  .render((
    <ApiProvider>
      <AppRoutes />
    </ApiProvider>
  ))
