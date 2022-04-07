/* eslint-disable @typescript-eslint/no-explicit-any */
import type {
  ConfigureStoreOptions,
  CreateSliceOptions,
  EnhancedStore,
  Reducer,
  ReducersMapObject,
  Action,
  AnyAction,
  Middleware,
  SliceCaseReducers,
  ImmutableStateInvariantMiddlewareOptions,
  SerializableStateInvariantMiddlewareOptions,
  Slice,
} from '@reduxjs/toolkit'
import type { ThunkMiddleware } from 'redux-thunk'
import { combineReducers, createSlice, configureStore } from '@reduxjs/toolkit'
import { useLayoutEffect, useState } from 'react'

interface ThunkOptions<E = any> {
  extraArgument: E
}

interface GetDefaultMiddlewareOptions {
  thunk?: boolean | ThunkOptions
  immutableCheck?: boolean | ImmutableStateInvariantMiddlewareOptions
  serializableCheck?: boolean | SerializableStateInvariantMiddlewareOptions
}

type ThunkMiddlewareFor<
  S,
  O extends GetDefaultMiddlewareOptions = Record<string, unknown>
  > = O extends {
    thunk: false
  }
  ? never
  : O extends { thunk: { extraArgument: infer E } }
    ? ThunkMiddleware<S, AnyAction, E>
    : ThunkMiddleware<S, AnyAction>

type Middlewares<S> = ReadonlyArray<Middleware<Record<string, never>, S>>

type GetState<S> = () => S
type UseSliceHook<S> = () => S

type RootState = Record<string, any>

export interface Payload<S> {
  payload: S
}

export const configureDynamicStore = <
  S extends RootState,
  A extends Action,
  M extends Middlewares<S> = [ThunkMiddlewareFor<S>]
>(options: Omit<ConfigureStoreOptions<S, A, M>, 'reducer'> = {}): {
  store: EnhancedStore<S, A, M>
  injectReducer: (key: string, reducer: Reducer) => void
} => {
  const store = configureStore<S, A, M>({
    ...options,
    reducer: <Reducer<S, A>>((s) => s),
  })

  let internalReducers = <ReducersMapObject<S, A>>{}

  const injectReducer = (key: string, reducer: Reducer) => {
    internalReducers = {
      ...internalReducers,
      [key]: reducer,
    }

    store.replaceReducer(combineReducers(internalReducers))
  }

  return { store, injectReducer }
}

export const createDynamicSlice = <
  SliceState,
  Name extends string,
  RS extends RootState,
  A extends Action,
  CaseReducers extends SliceCaseReducers<SliceState>,
  M extends Middlewares<RS>,
>(store: EnhancedStore<RS, A, M>, options: CreateSliceOptions<SliceState, CaseReducers, Name>): {
  slice: Slice<SliceState, CaseReducers, Name>
  getState: GetState<SliceState>
  useSlice: UseSliceHook<SliceState>
} => {
  const { name } = options
  const slice = createSlice(options)

  const getState = () => (store.getState())[name]

  const subscribe = (f: (state: SliceState) => void) => {
    let lastState = getState()

    return store.subscribe(() => {
      if (lastState !== getState()) {
        lastState = getState()
        f(lastState)
      }
    })
  }

  const useSlice = () => {
    const [state, setState] = useState<SliceState>(getState())
    useLayoutEffect(() => subscribe(setState), [setState])

    return state
  }

  return { slice, getState, useSlice }
}
