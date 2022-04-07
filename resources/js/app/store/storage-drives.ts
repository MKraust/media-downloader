import { createApi, IStorageDrive } from '@/api'
import { injectReducer, store } from '@/store'
import { createDynamicSlice, Payload } from '@/store/helpers'

const storeKey = 'storageDrives'

const { slice, getState, useSlice: useStorageDrives } = createDynamicSlice(store, {
  name: storeKey,
  initialState: {
    drives: [] as IStorageDrive[],
    isLoading: false,
  },
  reducers: {
    setLoading(state, { payload }: Payload<boolean>) {
      state.isLoading = payload
    },
    setList(state, { payload }: Payload<IStorageDrive[]>) {
      state.drives = payload
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setLoading, setList } = slice.actions

export { useStorageDrives }

export const loadStorageDrives = async () => {
  const { isLoading } = getState()

  if (isLoading) {
    return
  }

  dispatch(setLoading(true))
  dispatch(setList(await api.loadStorageDrives()))
  dispatch(setLoading(false))
}

injectReducer(storeKey, slice.reducer)
