import { createDynamicSlice } from '@mkraust/redux-dynamic'
import type { Payload } from '@mkraust/redux-dynamic'

import { createApi, IMedia, ITracker } from '@/api'
import { injectReducer, store } from '@/store'

interface TrackerState {
  items: IMedia[]
  isLoading: boolean
  error: boolean
  lastSearchQuery: string
  lastSearchIsEmpty: boolean
}

type TrackerPayload<T> = Payload<T & { trackerId: ITracker['id']}>

const storeKey = 'search'
const { api } = createApi()
const { dispatch } = store

const getInitialTrackerState = () => ({
  items: [],
  isLoading: false,
  error: false,
  lastSearchQuery: '',
  lastSearchIsEmpty: false,
})

const { slice, getState, useSlice } = createDynamicSlice(store, {
  name: storeKey,
  initialState: <Record<ITracker['id'], TrackerState>>{},
  reducers: {
    initTrackerState(state, { payload }: Payload<ITracker['id']>) {
      state[payload] = getInitialTrackerState()
    },
    setIsLoading(state, { payload }: TrackerPayload<{ isLoading: boolean }>) {
      const { trackerId, isLoading } = payload
      state[trackerId].isLoading = isLoading
    },
    setItems(state, { payload }: TrackerPayload<{ items: IMedia[] }>) {
      const { trackerId, items } = payload
      state[trackerId].items = items
    },
    setError(state, { payload }: TrackerPayload<{ error: boolean }>) {
      const { trackerId, error } = payload
      state[trackerId].error = error
    },
    setLastSearchQuery(state, { payload }: TrackerPayload<{ lastSearchQuery: string }>) {
      const { trackerId, lastSearchQuery } = payload
      state[trackerId].lastSearchQuery = lastSearchQuery
    },
    setLastSearchIsEmpty(state, { payload }: TrackerPayload<{ lastSearchIsEmpty: boolean }>) {
      const { trackerId, lastSearchIsEmpty } = payload
      state[trackerId].lastSearchIsEmpty = lastSearchIsEmpty
    },
  },
})

const { setIsLoading, setItems, setError, setLastSearchIsEmpty, setLastSearchQuery, initTrackerState } = slice.actions

const getTrackerState = (trackerId: ITracker['id']) => {
  if (!getState()[trackerId]) {
    dispatch(initTrackerState(trackerId))
  }

  return getState()[trackerId]
}

export const useSearch = (trackerId?: ITracker['id']) => {
  const state = useSlice()

  if (!trackerId) {
    return getInitialTrackerState()
  }

  if (!state[trackerId]) {
    dispatch(initTrackerState(trackerId))
    return getInitialTrackerState()
  }

  return state[trackerId]
}

export const searchMedia = async (trackerId: ITracker['id'], query: string) => {
  const { isLoading, lastSearchQuery } = getTrackerState(trackerId)

  if (!query || isLoading || lastSearchQuery === query) {
    return
  }

  dispatch(setIsLoading({ trackerId, isLoading: true }))
  dispatch(setError({ trackerId, error: false }))
  dispatch(setLastSearchIsEmpty({ trackerId, lastSearchIsEmpty: false }))

  try {
    const items = await api.search(trackerId, query)
    dispatch(setItems({ trackerId, items }))
    dispatch(setLastSearchQuery({ trackerId, lastSearchQuery: query }))

    if (items.length === 0) {
      dispatch(setLastSearchIsEmpty({ trackerId, lastSearchIsEmpty: true }))
    }
  } catch {
    dispatch(setError({ trackerId, error: true }))
  } finally {
    dispatch(setIsLoading({ trackerId, isLoading: false }))
  }
}

injectReducer(storeKey, slice.reducer)
