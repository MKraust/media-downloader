import { createSlice } from '@reduxjs/toolkit'

import { createApi, IDownload } from '@/api'
import type { RootState, ThunkAction } from '@/store/index'
import { AsyncInterval, confirm, runAsyncInterval } from '@/helpers'

const { api } = createApi()

let downloadsWatcher: AsyncInterval

export const downloadsSlice = createSlice({
  name: 'downloads',
  initialState: {
    list: [] as IDownload[],
  },
  reducers: {
    setList(state, { payload }) {
      state.list = payload
    },
    setDownloadOriginalState(state, { payload }: { payload: { hash: IDownload['hash']; originalState: IDownload['state_original'] } }) {
      const { hash, originalState } = payload
      const downloadIdx = state.list.findIndex((i) => i.hash === hash)
      if (!downloadIdx) {
        return
      }

      const download = state.list[downloadIdx]
      state.list[downloadIdx] = { ...download, state_original: originalState }
    },
  },
})

const { setList, setDownloadOriginalState } = downloadsSlice.actions

export const selectDownloads = (state: RootState) => state.downloads.list

export const loadDownloads = (): ThunkAction => async (dispatch) => {
  dispatch(setList(await api.loadDownloads()))
}

export const startWatchingDownloads = (): ThunkAction => (dispatch) => {
  if (downloadsWatcher?.isRunning()) {
    return
  }

  downloadsWatcher = runAsyncInterval(async () => {
    dispatch(loadDownloads())
  }, 1000)
}

export const stopWatchingDownloads = (): ThunkAction => () => {
  if (downloadsWatcher) {
    downloadsWatcher.stop()
  }
}

export const deleteDownload = ({ hash, media, torrent }: IDownload): ThunkAction => async (dispatch, getState) => {
  const { downloads: { list } } = getState()
  const downloadName = media.title + (torrent.content_type !== 'movie' ? ` ${torrent.name}` : '')

  if (await confirm('Удалить загрузку?', downloadName)) {
    await api.deleteDownload(hash)
    const filteredList = list.filter((download) => download.hash !== hash)

    dispatch(setList(filteredList))
  }
}

export const pauseDownload = (hash: IDownload['hash']): ThunkAction => async (dispatch) => {
  dispatch(setDownloadOriginalState({ hash, originalState: 'pausedDL' }))
  await api.pauseDownload(hash)
}

export const resumeDownload = (hash: IDownload['hash']): ThunkAction => async (dispatch) => {
  dispatch(setDownloadOriginalState({ hash, originalState: 'pausedDL' }))
  await api.resumeDownload(hash)
}

export default downloadsSlice.reducer
