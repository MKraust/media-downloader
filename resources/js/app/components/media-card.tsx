import { Link } from 'react-router-dom'
import { FC } from 'react'
import clsx from 'clsx'
import { Card } from 'react-bootstrap'

import { IMedia } from '@/api'

export interface MediaCardProps {
  media: IMedia
  className?: string
}

export const MediaCard: FC<MediaCardProps> = ({ media, className }) => {
  return (
    <Card className={clsx('ribbon ribbon-start shadow-sm', className)}>
      {media.series_count && (
        <div className="ribbon-label bg-danger" style={{ top: '24px' }}>{media.series_count}</div>
      )}
      <Link to={`/media/${media.id}`}>
        <Card.Img src={media.poster} style={{ width: '100%' }} alt={media.title} />
      </Link>
      <Card.Body className="d-flex flex-column justify-content-center" style={{ padding: '1.5rem' }}>
        <Card.Title className="text-center mb-0">
          <Link to={`/media/${media.id}`} className="hoverable">
            {media.title}
          </Link>
        </Card.Title>
        {media.original_title && (
          <Card.Text className="text-center text-muted mb-0 mt-1">
            {media.original_title}
          </Card.Text>
        )}
      </Card.Body>
    </Card>
  )
}
