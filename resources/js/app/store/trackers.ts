import { createApi, ITracker } from '@/api'
import { injectReducer, store } from '@/store'
import { createDynamicSlice, Payload } from '@/store/helpers'

const storeKey = 'trackers'

const { slice, getState, useSlice: useTrackers } = createDynamicSlice(store, {
  name: storeKey,
  initialState: {
    trackers: [] as ITracker[],
    isLoading: false,
  },
  reducers: {
    setLoading(state, { payload }: Payload<boolean>) {
      state.isLoading = payload
    },
    setList(state, { payload }: Payload<ITracker[]>) {
      state.trackers = payload
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setLoading, setList } = slice.actions

export { useTrackers }

export const selectTrackerById = (id?: ITracker['id']) => getState().trackers.find((i) => i.id === id)

export const loadTrackers = async () => {
  const { isLoading } = getState()

  if (isLoading) {
    return
  }

  dispatch(setLoading(true))
  dispatch(setList(await api.loadTrackers()))
  dispatch(setLoading(false))
}

injectReducer(storeKey, slice.reducer)
