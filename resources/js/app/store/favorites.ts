import { createSlice } from '@reduxjs/toolkit'

import { createApi, IMedia } from '@/api'
import { Dispatch, RootState } from '@/store/index'

const { api } = createApi()

export const favoritesSlice = createSlice({
  name: 'favorites',
  initialState: {
    list: [] as IMedia[],
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

const { setLoading, setList } = favoritesSlice.actions

export const selectIsLoadingFavorites = (state: RootState) => state.favorites.isLoading

export const loadFavorites = () => async (dispatch: Dispatch) => {
  dispatch(setLoading(true))
  dispatch(setList(await api.loadFavorites()))
  dispatch(setLoading(false))
}

export default favoritesSlice.reducer
