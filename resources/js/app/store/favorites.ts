import { createDynamicSlice } from '@mkraust/redux-dynamic'
import type { Payload } from '@mkraust/redux-dynamic'

import { createApi, IMedia } from '@/api'
import { injectReducer, store } from '@/store'

const storeKey = 'favorites'

const { slice, getState, useSlice: useFavorites } = createDynamicSlice(store, {
  name: storeKey,
  initialState: {
    favorites: [] as IMedia[],
    isLoading: false,
  },
  reducers: {
    setLoading(state, { payload }: Payload<boolean>) {
      state.isLoading = payload
    },
    setFavorites(state, { payload }: Payload<IMedia[]>) {
      state.favorites = payload
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setFavorites, setLoading } = slice.actions

const loadFavorites = async () => {
  const { isLoading } = getState()

  if (isLoading) {
    return
  }

  dispatch(setLoading(true))
  const favorites = await api.loadFavorites()
  dispatch(setFavorites(favorites))
  dispatch(setLoading(false))
}

injectReducer(storeKey, slice.reducer)

export {
  useFavorites,
  loadFavorites,
}
