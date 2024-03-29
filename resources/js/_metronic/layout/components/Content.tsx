import React, { useEffect } from 'react'
import { useLocation } from 'react-router'
import clsx from 'clsx'

import { useLayout } from '../core'
import { DrawerComponent } from '../../assets/ts/components'

import { PropsWithOnlyChildren } from '@/helpers'

const Content: React.FC<PropsWithOnlyChildren> = ({ children }) => {
  const { classes } = useLayout()
  const location = useLocation()
  useEffect(() => {
    DrawerComponent.hideAll()
  }, [location])

  return (
    <div id="kt_content_container" className={clsx(classes.contentContainer.join(' '), 'd-flex flex-column flex-column-fluid')}>
      {children}
    </div>
  )
}

export { Content }
