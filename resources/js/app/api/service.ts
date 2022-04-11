import { Api } from '@mkraust/api-base'
import type { With } from '@mkraust/types'

import { ServiceHandleError } from './helpers'
import { IDownload, IFinishedDownload, IMedia, IStorageDrive, ITorrent, ITracker } from './models'

export class ApiService extends Api {

  @ServiceHandleError(() => [])
  async loadTrackers(): Promise<ITracker[]> {
    const { data } = await this.http.get('/trackers')
    return data
  }

  @ServiceHandleError(() => [])
  async loadFavorites(): Promise<IMedia[]> {
    const { data } = await this.http.get('/favorites/list')
    return data
  }

  async loadMedia(id: IMedia['id']): Promise<With<IMedia, 'torrents'> | null> {
    const { data } = await this.http.get('/media', {
      query: { id },
    })

    return data
  }

  async addToFavorites(id: IMedia['id']) {
    await this.http.post('/favorites/add', {
      body: { id },
    })
  }

  async removeFromFavorites(id: IMedia['id']) {
    await this.http.post('/favorites/remove', {
      body: { id },
    })
  }

  async startDownload(id: ITorrent['id']) {
    await this.http.get('/download', {
      query: { id },
    })
  }

  async search(trackerId: ITracker['id'], searchQuery: string, offset = 0): Promise<IMedia[]> {
    const { data } = await this.http.get('/search', {
      query: {
        tracker_id: trackerId,
        search_query: searchQuery,
        offset,
      },
    })

    return data
  }

  async loadDownloads(): Promise<IDownload[]> {
    const { data } = await this.http.get('/download/list')

    return data
  }

  @ServiceHandleError()
  async pauseDownload(hash: IDownload['hash']) {
    await this.http.post('/download/pause', { body: { hash } })
  }

  @ServiceHandleError()
  async resumeDownload(hash: IDownload['hash']) {
    await this.http.post('/download/resume', { body: { hash } })
  }

  @ServiceHandleError()
  async deleteDownload(hash: IDownload['hash']) {
    await this.http.post('/download/delete', { body: { hash } })
  }

  async loadStorageDrives(): Promise<IStorageDrive[]> {
    const { data } = await this.http.get('/info/storage')

    return data
  }

  @ServiceHandleError(() => [])
  async loadFinishedDownloads(): Promise<IFinishedDownload[]> {
    const { data } = await this.http.get('/download/finished/list')

    return data
  }

  async deleteFinishedDownload(id: IFinishedDownload['id']): Promise<IFinishedDownload> {
    const { data } = await this.http.post('/download/finished/delete', {
      body: { id },
    })

    return data
  }

  async revertFinishedDownloadFileNames(id: IFinishedDownload['id']): Promise<IFinishedDownload> {
    const { data } = await this.http.post('/download/finished/revert-renaming', {
      body: { id },
    })

    return data
  }

  async renameFinishedDownloadFiles(id: IFinishedDownload['id'], title: string): Promise<IFinishedDownload> {
    const { data } = await this.http.post('/download/finished/rename', {
      body: { id, title },
    })

    return data
  }
}
