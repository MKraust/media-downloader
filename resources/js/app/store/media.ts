import { createSlice } from '@reduxjs/toolkit'

import type { RootState, ThunkAction } from '@/store/index'
import { createApi, IMedia } from '@/api'
import { With } from '@/helpers'

const { api } = createApi()

export const mediaSlice = createSlice({
  name: 'media',
  initialState: {
    item: null as With<IMedia, 'torrents'> | null,
    isLoading: false,
    error: false,
  },
  reducers: {
    setIsLoading(state, { payload }) {
      state.isLoading = payload
    },
    setItem(state, { payload }) {
      state.item = payload
    },
    setError(state, { payload }) {
      state.error = payload
    },
  },
})

const { setIsLoading, setItem, setError } = mediaSlice.actions

export const selectMedia = (state: RootState) => state.media.item
export const selectIsLoadingMedia = (state: RootState) => state.media.isLoading
export const selectError = (state: RootState) => state.media.error

export const loadMedia = (id: IMedia['id']): ThunkAction => async (dispatch, getState) => {
  const { media: { isLoading } } = getState()

  if (isLoading) {
    return
  }

  dispatch(setIsLoading(true))
  dispatch(setError(false))

  try {
    const media = await api.loadMedia(id)
    dispatch(setItem(media))
  } catch {
    dispatch(setError(true))
  } finally {
    dispatch(setIsLoading(false))
  }
}

export default mediaSlice.reducer
