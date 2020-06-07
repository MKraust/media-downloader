import { requestApi } from './apiCore';

const BACKEND_API = `${window.location.protocol}//${window.location.host}/api`;
const LOAD_TRACKERS = BACKEND_API + '/trackers';
const SEARCH_MEDIA_ITEMS = BACKEND_API + '/search';
const LOAD_MEDIA = BACKEND_API + '/media';
const START_DOWNLOAD = BACKEND_API + '/download';

export async function loadTrackers() {
    return await requestApi({ method: 'GET', url: LOAD_TRACKERS });
}

export async function search(tracker, query) {
    const params = { tracker, query };
    return await requestApi({ method: 'GET', url: SEARCH_MEDIA_ITEMS, getParams: params });
}

export async function loadMedia(tracker, id) {
    const params = { tracker, id };
    return await requestApi({ method: 'GET', url: LOAD_MEDIA, getParams: params });
}

export async function startDownload(tracker, url, type) {
    return await requestApi({ method: 'GET', url: START_DOWNLOAD, getParams: { tracker, url, type }});
}
