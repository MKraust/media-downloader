import { configureStore } from '@reduxjs/toolkit'
import { TypedUseSelectorHook, useDispatch as useBaseDispatch, useSelector as useBaseSelector } from 'react-redux'

export const store = configureStore({
  reducer: {},
})

export type RootState = ReturnType<typeof store.getState>
export type Dispatch = typeof store.dispatch

export const useDispatch = () => useBaseDispatch<Dispatch>()
export const useSelector: TypedUseSelectorHook<RootState> = useBaseSelector
