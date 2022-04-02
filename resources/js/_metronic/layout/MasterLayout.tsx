import { ReactFragment, ReactNode, useEffect, FC } from 'react'
import { Outlet } from 'react-router-dom'
import { Aside } from './components/aside/Aside'
import { HeaderWrapper } from './components/header/HeaderWrapper'
import { Toolbar } from './components/toolbar/Toolbar'
import { ScrollTop } from './components/ScrollTop'
import { Content } from './components/Content'
import { PageDataProvider } from './core'
import { useLocation } from 'react-router-dom'
import { MenuComponent } from '../assets/ts/components'

export interface MasterLayoutProps {
  menu?: ReactNode | ReactFragment
}

const MasterLayout: FC<MasterLayoutProps> = ({ menu }) => {
  const location = useLocation()

  useEffect(() => {
    setTimeout(() => {
      MenuComponent.reinitialization()
    }, 500)
  }, [])

  useEffect(() => {
    setTimeout(() => {
      MenuComponent.reinitialization()
    }, 500)
  }, [location.key])

  return (
    <PageDataProvider>
      <div className='page d-flex flex-row flex-column-fluid'>
        <Aside menu={menu} />
        <div className='wrapper d-flex flex-column flex-row-fluid' id='kt_wrapper'>
          <HeaderWrapper />

          <div id='kt_content' className='content d-flex flex-column flex-column-fluid'>
            <div className='post d-flex flex-column-fluid' id='kt_post'>
              <Content>
                <Outlet />
              </Content>
            </div>
          </div>
        </div>
      </div>

      <ScrollTop />
    </PageDataProvider>
  )
}

export { MasterLayout }
