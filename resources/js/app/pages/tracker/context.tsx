import { createContext, FC, useContext } from 'react'

import { ITracker } from '@/api'

const TrackerContext = createContext<ITracker | null>(null)

interface TrackerProviderProps {
  tracker: ITracker
}

export const TrackerProvider: FC<TrackerProviderProps> = ({ children, tracker }) => (
  <TrackerContext.Provider value={tracker}>
    {children}
  </TrackerContext.Provider>
)

export const useCurrentTracker = () => {
  return useContext(TrackerContext)
}
