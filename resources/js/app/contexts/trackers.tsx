import { createContext, FC, useContext, useEffect, useState } from 'react'

import { ITracker, useApi } from '@/api'

const TrackersContext = createContext<ITracker[]>([])

const useTrackersState = () => {
  const api = useApi()
  const [trackers, setTrackers] = useState<ITracker[]>(() => [])

  useEffect(() => {
    api.loadTrackers().then((trackers) => setTrackers(trackers))
  }, [])

  return trackers
}

export const TrackersProvider: FC = ({ children }) => {
  const trackers = useTrackersState()

  return <TrackersContext.Provider value={trackers}>{children}</TrackersContext.Provider>
}

export const useTrackers = () => {
  return useContext(TrackersContext)
}
