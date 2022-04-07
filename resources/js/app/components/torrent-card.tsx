import { Badge, Card } from 'react-bootstrap'
import { FC } from 'react'

import { ITorrent, useApi } from '@/api'
import { Icon, IconProps } from '@/components/icon'
import { confirm, notifySuccess } from '@/helpers'

export interface TorrentProps {
  value: ITorrent
}

export const TorrentCard: FC<TorrentProps> = ({ value }) => {
  const { id, name, voice_acting: voiceActing, quality, size, downloads } = value

  const api = useApi()

  const handleDownload = async () => {
    if (await confirm('Скачать?', name)) {
      await api.startDownload(id)
      notifySuccess(name, 'Загрузка началась')
    }
  }

  const renderAttrBadge = (icon: IconProps['name'], value: string | number) => (
    <Badge bg="primary" className="d-inline-flex align-items-center gap-2">
      <Icon name={icon} className="text-white" /> {value}
    </Badge>
  )

  return (
    <Card className="bg-white shadow-xs" onClick={() => handleDownload()}>
      <div className="card-body px-8 py-6">
        <div className="d-flex justify-content-between align-items-center">
          <Card.Title className="mb-1">
            {name}
          </Card.Title>
        </div>
        {voiceActing && (
          <Card.Text className="mb-4">
            {voiceActing}
          </Card.Text>
        )}
        <div className="d-flex gap-2">
          {quality && renderAttrBadge('photo-video', quality)}
          {size && renderAttrBadge('weight-hanging', size)}
          {downloads && renderAttrBadge('download', downloads)}
        </div>
      </div>
    </Card>
  )
}
