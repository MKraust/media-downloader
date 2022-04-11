import { createDynamicSlice } from '@mkraust/redux-dynamic'
import type { Payload } from '@mkraust/redux-dynamic'

import { createApi, IFinishedDownload } from '@/api'
import { injectReducer, store } from '@/store'

const storeKey = 'downloadsHistory'

const { slice, getState, useSlice: useDownloadsHistory } = createDynamicSlice(store, {
  name: storeKey,
  initialState: {
    downloads: <IFinishedDownload[]>[],
    isLoading: false,
  },
  reducers: {
    setLoading(state, { payload }: Payload<boolean>) {
      state.isLoading = payload
    },
    setDownloads(state, { payload }: Payload<IFinishedDownload[]>) {
      state.downloads = payload
    },
    replaceDownload(state, { payload }: Payload<IFinishedDownload>) {
      state.downloads = state.downloads.map((i) => i.id === payload.id ? payload : i)
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setLoading, setDownloads, replaceDownload } = slice.actions

const loadFinishedDownloads = async () => {
  const { isLoading } = getState()

  if (isLoading) {
    return
  }

  dispatch(setLoading(true))
  const items = await api.loadFinishedDownloads()
  dispatch(setDownloads(items))
  dispatch(setLoading(false))
}

const deleteFinishedDownload = async (id: IFinishedDownload['id']) => {
  const updatedDownload = await api.deleteFinishedDownload(id)
  dispatch(replaceDownload(updatedDownload))
}

const revertFinishedDownloadFileNames = async (id: IFinishedDownload['id']) => {
  const updatedDownload = await api.revertFinishedDownloadFileNames(id)
  dispatch(replaceDownload(updatedDownload))
}

const renameFinishedDownloadFiles = async (id: IFinishedDownload['id'], title: string) => {
  const updatedDownload = await api.renameFinishedDownloadFiles(id, title)
  dispatch(replaceDownload(updatedDownload))
}

injectReducer(storeKey, slice.reducer)

export {
  useDownloadsHistory,
  loadFinishedDownloads,
  deleteFinishedDownload,
  revertFinishedDownloadFileNames,
  renameFinishedDownloadFiles,
}
