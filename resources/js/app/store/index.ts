import { configureStore } from '@reduxjs/toolkit'
import { useDispatch as useBaseDispatch, useSelector as useBaseSelector } from 'react-redux'
import type { TypedUseSelectorHook } from 'react-redux'
import type { AnyAction } from 'redux'
import type { ThunkAction as BaseThunkAction } from 'redux-thunk'

import favoritesReducer from './favorites'
import trackersReducer from './trackers'
import downloadsReducer from './downloads'
import mediaReducer from './media'
import searchReducer from './search'

export const store = configureStore({
  reducer: {
    trackers: trackersReducer,
    favorites: favoritesReducer,
    downloads: downloadsReducer,
    media: mediaReducer,
    search: searchReducer,
  },
})

export type RootState = ReturnType<typeof store.getState>
export type Dispatch = typeof store.dispatch
export type ThunkAction<R = void> = BaseThunkAction<R, RootState, unknown, AnyAction>

export const useDispatch = () => useBaseDispatch<Dispatch>()
export const useSelector: TypedUseSelectorHook<RootState> = useBaseSelector
