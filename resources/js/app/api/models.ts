import type { With } from '@mkraust/types'

export enum ContentType {
  movie = 'movie',
  anime = 'anime',
  series = 'series',
}

export interface ITracker {
  id: string
  title: string
  icon: string
}

export interface IMedia {
  id: string
  url: string
  tracker_id: ITracker['id']
  title: string
  original_title?: string
  poster: string
  series_count?: string
  is_favorite: boolean
  added_to_favorites_at?: string
  torrents?: ITorrent[]
}

export interface ITorrent {
  id: number
  name: string
  url: string
  content_type: ContentType
  voice_acting?: string
  quality?: string
  size?: string
  size_int?: number
  downloads?: number
  season?: [number, number]
  created_at: string
  updated_at: string
  media_id: IMedia['id']
  media?: IMedia
}

export interface IDownload {
  hash: string
  name: string
  download_speed_in_bytes_per_second: number
  estimate_in_seconds: number
  size_in_bytes: number
  state_original: 'downloading' | 'error' | 'uploading' | 'metaDL' | 'checkingDL' | 'checkingUP' | 'pausedDL' | 'pausedUP' | 'stalledDL' | 'stalledUP'
  progress: string
  media: IMedia
  torrent: ITorrent
}

export interface IRenameLog {
  from: string
  to: string
}

export interface IFinishedDownloadMeta {
  files: string[]
  rename_log: IRenameLog[]
}

export interface IFinishedDownload {
  id: number
  torrent: With<ITorrent, 'media'>
  finished_at: string
  path: string
  exists: boolean
  meta: IFinishedDownloadMeta
  is_deleted: boolean
}

export interface IStorageDrive {
  name: string
  available: number
  total: number
  used: number
  usage_percent: number
}
