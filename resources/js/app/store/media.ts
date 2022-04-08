import type { With } from '@mkraust/types'
import { createDynamicSlice } from '@mkraust/redux-dynamic'
import type { Payload } from '@mkraust/redux-dynamic'

import { createApi, IMedia } from '@/api'
import { injectReducer, store } from '@/store'

const storeKey = 'media'

const { slice, getState, useSlice: useMedia } = createDynamicSlice(store, {
  name: storeKey,
  initialState: {
    media: null as With<IMedia, 'torrents'> | null,
    isLoading: false,
    error: false,
  },
  reducers: {
    setIsLoading(state, { payload }: Payload<boolean>) {
      state.isLoading = payload
    },
    setItem(state, { payload }: Payload<With<IMedia, 'torrents'> | null>) {
      state.media = payload
    },
    setError(state, { payload }: Payload<boolean>) {
      state.error = payload
    },
    setIsFavorite(state, { payload }: Payload<boolean>) {
      if (state.media) {
        state.media.is_favorite = payload
      }
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setIsLoading, setItem, setError, setIsFavorite } = slice.actions

export { useMedia }

export const loadMedia = async (id: IMedia['id']) => {
  const { isLoading } = getState()

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

export const toggleMediaIsFavorite = async () => {
  const { media } = getState()

  if (!media) {
    return
  }

  if (media.is_favorite) {
    await api.removeFromFavorites(media.id)
    dispatch(setIsFavorite(false))
  } else {
    await api.addToFavorites(media.id)
    dispatch(setIsFavorite(true))
  }
}

injectReducer(storeKey, slice.reducer)
