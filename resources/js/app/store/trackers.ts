import { createSlice } from '@reduxjs/toolkit'

import { createApi, ITracker } from '@/api'
import { RootState, ThunkAction } from '@/store'

const { api } = createApi()

export const trackersSlice = createSlice({
  name: 'trackers',
  initialState: {
    list: [] as ITracker[],
    isLoading: false,
  },
  reducers: {
    setLoading(state, { payload }) {
      state.isLoading = payload
    },
    setList(state, { payload }) {
      state.list = payload
    },
  },
})

const { setLoading, setList } = trackersSlice.actions

export const selectIsLoadingTrackers = (state: RootState) => state.trackers.isLoading
export const selectTrackers = (state: RootState) => state.trackers.list
export const selectTrackerById = (id?: ITracker['id']) => (state: RootState) => state.trackers.list.find((i) => i.id === id)

export const loadTrackers = (): ThunkAction => async (dispatch, getState) => {
  const { trackers: { isLoading } } = getState()

  if (isLoading) {
    return
  }

  dispatch(setLoading(true))
  dispatch(setList(await api.loadTrackers()))
  dispatch(setLoading(false))
}

export default trackersSlice.reducer
