import { createSlice } from '@reduxjs/toolkit'

import type { RootState, ThunkAction } from '@/store/index'
import { createApi, IMedia, ITracker } from '@/api'

const { api } = createApi()

export const searchSlice = createSlice({
  name: 'search',
  initialState: {
    items: {} as Record<ITracker['id'], IMedia[]>,
    isLoading: {} as Record<ITracker['id'], boolean>,
    error: {} as Record<ITracker['id'], boolean>,
    lastSearchQuery: {} as Record<ITracker['id'], string>,
    lastSearchIsEmpty: {} as Record<ITracker['id'], boolean>,
  },
  reducers: {
    setIsLoading(state, { payload }) {
      const { trackerId, isLoading } = payload
      state.isLoading = {
        ...state.isLoading,
        [trackerId]: isLoading,
      }
    },
    setItems(state, { payload }) {
      const { trackerId, items } = payload
      state.items = {
        ...state.items,
        [trackerId]: items,
      }
    },
    setError(state, { payload }) {
      const { trackerId, error } = payload
      state.error = {
        ...state.error,
        [trackerId]: error,
      }
    },
    setLastSearchQuery(state, { payload }) {
      const { trackerId, query } = payload
      state.lastSearchQuery = {
        ...state.lastSearchQuery,
        [trackerId]: query,
      }
    },
    setLastSearchIsEmpty(state, { payload }) {
      const { trackerId, isEmpty } = payload
      state.lastSearchQuery = {
        ...state.lastSearchQuery,
        [trackerId]: isEmpty,
      }
    },
  },
})

const { setIsLoading, setItems, setError, setLastSearchIsEmpty, setLastSearchQuery } = searchSlice.actions

export const selectFoundMedia = (trackerId?: ITracker['id']) => (state: RootState) => (trackerId && state.search.items[trackerId]) || []
export const selectIsLoadingSearchResults = (trackerId?: ITracker['id']) => (state: RootState) => (trackerId && state.search.isLoading[trackerId]) || false
export const selectError = (trackerId?: ITracker['id']) => (state: RootState) => (trackerId && state.search.error[trackerId]) || false
export const selectLastSearchQuery = (trackerId?: ITracker['id']) => (state: RootState) => (trackerId && state.search.lastSearchQuery[trackerId]) || ''
export const selectIsLastSearchEmpty = (trackerId?: ITracker['id']) => (state: RootState) => (trackerId && state.search.lastSearchIsEmpty[trackerId]) || false

export const searchMedia = (trackerId: ITracker['id'], query: string): ThunkAction => async (dispatch, getState) => {
  const state = getState()
  const isLoading = selectIsLoadingSearchResults(trackerId)(state)
  const lastQuery = selectLastSearchQuery(trackerId)(state)

  if (!query || isLoading || lastQuery === query) {
    return
  }

  dispatch(setIsLoading({ trackerId, isLoading: true }))
  dispatch(setError({ trackerId, error: false }))
  dispatch(setLastSearchIsEmpty({ trackerId, isEmpty: false }))

  try {
    const items = await api.search(trackerId, query)
    dispatch(setItems({ trackerId, items }))
    dispatch(setLastSearchQuery({ trackerId, query }))

    if (items.length === 0) {
      dispatch(setLastSearchIsEmpty({ trackerId, isEmpty: true }))
    }
  } catch {
    dispatch(setError({ trackerId, error: true }))
  } finally {
    dispatch(setIsLoading({ trackerId, isLoading: false }))
  }
}

export default searchSlice.reducer
