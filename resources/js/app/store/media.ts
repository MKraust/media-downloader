import { createApi, IMedia } from '@/api'
import { With } from '@/helpers'
import { createDynamicSlice, Payload } from '@/store/helpers'
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
    setError(state, { payload }) {
      state.error = payload
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setIsLoading, setItem, setError } = slice.actions

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

injectReducer(storeKey, slice.reducer)
