import { FC } from 'react'

export const Toolbar: FC = ({ children }) => (
  <div className='toolbar' id='kt_toolbar'>
    <div id='kt_toolbar_container' className='d-flex flex-stack'>
      {children}
    </div>
  </div>
)
