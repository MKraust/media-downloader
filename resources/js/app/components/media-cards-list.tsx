import { Col, Row } from 'react-bootstrap'
import { FC } from 'react'

import { MediaCard } from '@/components/media-card'
import { IMedia } from '@/api'

export interface MediaCardsListProps {
  mediaList: IMedia[]
}

export const MediaCardsList: FC<MediaCardsListProps> = ({ mediaList }) => {
  return (
    <Row>
      {mediaList.map((media) => (
        <Col key={media.id} xs={12} sm={6} md={4} xl={3} className={'mb-7 d-flex'}>
          <MediaCard media={media} className="align-self-stretch w-100" />
        </Col>
      ))}
    </Row>
  )
}
