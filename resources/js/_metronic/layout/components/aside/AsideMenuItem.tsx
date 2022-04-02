import React from 'react'
import clsx from 'clsx'
import { Link } from 'react-router-dom'
import { useLocation } from 'react-router'
import { checkIsActive, KTSVG } from '../../../helpers'
import { useLayout } from '../../core'

export type AsideMenuItemProps = {
  to: string
  title: string
  icon?: string
  fontIcon?: string
  image?: string
  hasBullet?: boolean
}

const AsideMenuItem: React.FC<AsideMenuItemProps> = ({
  children,
  to,
  title,
  icon,
  fontIcon,
  image,
  hasBullet = false,
}) => {
  const { pathname } = useLocation()
  const isActive = checkIsActive(pathname, to)

  return (
    <div className='menu-item'>
      <Link className={clsx('menu-link without-sub', { active: isActive })} to={to}>
        {hasBullet && (
          <span className='menu-bullet'>
            <span className='bullet bullet-dot' />
          </span>
        )}
        {icon && (
          <span className='menu-icon'>
            <KTSVG path={icon} className='svg-icon-2' />
          </span>
        )}
        {fontIcon && <i className={clsx('bi fs-3', fontIcon)} />}
        {image && <img className='aside-menu-item-image' src={image} alt='' />}
        <span className='menu-title'>{title}</span>
      </Link>
      {children}
    </div>
  )
}

export { AsideMenuItem }
