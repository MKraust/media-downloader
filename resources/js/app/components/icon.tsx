import { FC, useMemo } from 'react'
import clsx from 'clsx'

export interface IconProps {
  name: string
  className?: string
  style?: 'light' | 'duo' | 'solid'
  size?:
    'base' | 'fluid' |
    1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 |
    '1' | '2' | '3' | '4' | '5' | '6' | '7' | '8' | '9' | '10' |
    '2x' | '2qx' | '2hx' | '2tx' |
    '3x' | '3qx' | '3hx' | '3tx' |
    '4x' | '4qx' | '4hx' | '4tx' |
    '5x' | '5qx' | '5hx' | '5tx'
}

export const Icon: FC<IconProps> = ({ name, className, size = 'base', style = 'light' }) => {
  const iconType = useMemo(() => {
    switch (style) {
      case 'duo':
        return 'd'
      case 'light':
        return 'l'
      case 'solid':
        return 's'
    }
  }, [style])

  return <i className={clsx(`fa${iconType} fa-${name} fs-${size}`, className)} />
}
