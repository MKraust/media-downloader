import { Spinner } from 'react-bootstrap'

export const Preloader = () => (
  <div className="d-flex justify-content-center align-items-center flex-column-fluid flex-row-fluid">
    <Spinner animation="grow" variant="primary" />
  </div>
)
