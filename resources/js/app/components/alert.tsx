import { FC, ReactNode } from 'react'
import { Alert as BaseAlert } from 'react-bootstrap'
import type { AlertProps as BaseAlertProps } from 'react-bootstrap'
import clsx from 'clsx'

import { Icon } from '@/components'

export interface AlertProps extends Omit<BaseAlertProps, 'title'> {
  title?: ReactNode
  text?: ReactNode
  icon?: string
}

export const Alert: FC<AlertProps> = (props) => {
  const { children, icon, title, text, variant = 'primary' } = props
  const omittedProps = ['title', 'icon', 'text']
  const preparedProps = Object.fromEntries(Object.entries(props).filter(([key]) => !omittedProps.includes(key)))

  return (
    <BaseAlert {...preparedProps} className={clsx(!children && 'd-flex flex-column flex-sm-row align-items-center gap-4')}>
      {children || (
        <>
          {icon && <Icon name={icon} className={`text-${variant}`} />}
          <div className="d-flex flex-column">
            {title && <h5 className={`mb-1 text-${variant}`}>{title}</h5>}
            <span>{text}</span>
          </div>
        </>
      )}
    </BaseAlert>
  )
}
