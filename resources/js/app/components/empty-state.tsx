import type { Variant } from 'react-bootstrap/types'
import { FC } from 'react'

import { Icon, IconProps } from '@/components/icon'
import { PropsWithOnlyChildren } from '@/helpers'

export interface EmptyStateProps extends PropsWithOnlyChildren {
  icon: string
  iconStyle?: IconProps['style']
  variant?: Variant
}

export const EmptyState: FC<EmptyStateProps> = ({ icon, iconStyle, variant, children }) => {
  return (
    <div className="d-flex flex-column align-items-center justify-content-center gap-5 w-100 h-100">
      <Icon name={icon} size="5tx" style={iconStyle} className={`text-${variant || 'gray-400'}`} />
      <div className="text-center text-muted fs-1">
        {children}
      </div>
    </div>
  )
}
