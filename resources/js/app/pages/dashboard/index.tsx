import { ReactNode, useEffect, useState } from 'react'
import { Card, Col, Row } from 'react-bootstrap'

import { PageTitle } from '@metronic'
import { Preloader, StorageDrive } from '@/components'
import { IStorageDrive, useApi } from '@/api'

const DashboardPage = () => {
  const api = useApi()
  const [isLoading, setLoading] = useState(false)
  const [drives, setDrives] = useState<IStorageDrive[]>(() => [])

  const fetchDrives = async () => {
    setLoading(true)
    setDrives(await api.loadStorageDrives())
    setLoading(false)
  }

  useEffect(() => {
    fetchDrives()
  }, [])

  const renderContent = (content: ReactNode) => (
    <>
      <PageTitle>Сводка</PageTitle>
      {content}
    </>
  )

  if (isLoading) {
    return renderContent(<Preloader />)
  }

  return renderContent((
    <Row>
      <Col md={4}>
        <Card>
          <Card.Header>
            <Card.Title>Хранилище</Card.Title>
          </Card.Header>
          <Card.Body>
            <div className="d-flex flex-column gap-5">
              {drives.map((drive, idx) => (
                <StorageDrive key={idx} value={drive} />
              ))}
            </div>
          </Card.Body>
        </Card>
      </Col>
    </Row>
  ))
}

export default DashboardPage
