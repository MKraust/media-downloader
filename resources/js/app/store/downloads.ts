import { AsyncInterval, runAsyncInterval } from '@mkraust/async-interval'
import { createDynamicSlice } from '@mkraust/redux-dynamic'
import type { Payload } from '@mkraust/redux-dynamic'

import { createApi, IDownload, ITorrent } from '@/api'
import { confirm } from '@/helpers'
import { injectReducer, store } from '@/store'

let downloadsWatcher: AsyncInterval

const storeKey = 'downloads'

const { slice, useSlice: useDownloads, getState } = createDynamicSlice(store, {
  name: storeKey,
  initialState: {
    downloads: [] as IDownload[],
  },
  reducers: {
    setList(state, { payload }: Payload<IDownload[]>) {
      state.downloads = payload
    },
    setDownloadOriginalState(state, { payload }: Payload<{
      hash: IDownload['hash']
      originalState: IDownload['state_original']
    }>) {
      const { hash, originalState } = payload
      const downloadIdx = state.downloads.findIndex((i) => i.hash === hash)
      if (!downloadIdx) {
        return
      }

      const download = state.downloads[downloadIdx]
      state.downloads[downloadIdx] = { ...download, state_original: originalState }
    },
  },
})

const { api } = createApi()
const { dispatch } = store
const { setList, setDownloadOriginalState } = slice.actions

export { useDownloads }

export const loadDownloads = async () => {
  dispatch(setList(await api.loadDownloads()))
}

export const startWatchingDownloads = () => {
  if (downloadsWatcher?.isRunning()) {
    return
  }

  downloadsWatcher = runAsyncInterval(async () => {
    await loadDownloads()
  }, 1000)
}

export const stopWatchingDownloads = () => {
  if (downloadsWatcher) {
    downloadsWatcher.stop()
  }
}

export const startDownload = (id: ITorrent['id']) => api.startDownload(id)

export const deleteDownload = async ({ hash, media, torrent }: IDownload) => {
  const { downloads } = getState()
  const downloadName = media.title + (torrent.content_type !== 'movie' ? ` ${torrent.name}` : '')

  if (await confirm('Удалить загрузку?', downloadName)) {
    await api.deleteDownload(hash)
    const filteredList = downloads.filter((download) => download.hash !== hash)

    dispatch(setList(filteredList))
  }
}

export const pauseDownload = async (hash: IDownload['hash']) => {
  dispatch(setDownloadOriginalState({ hash, originalState: 'pausedDL' }))
  await api.pauseDownload(hash)
}

export const resumeDownload = async (hash: IDownload['hash']) => {
  dispatch(setDownloadOriginalState({ hash, originalState: 'pausedDL' }))
  await api.resumeDownload(hash)
}

injectReducer(storeKey, slice.reducer)
