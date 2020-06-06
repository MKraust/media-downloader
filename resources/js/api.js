import { requestApi } from './apiCore';

const BACKEND_API = 'http://localhost:8000/api';
const LOAD_TRACKERS = BACKEND_API + '/trackers';
const SEARCH_MEDIA_ITEMS = BACKEND_API + '/search';

export async function loadTrackers() {
    const trackers = await requestApi({ method: 'GET', url: LOAD_TRACKERS });
    console.log(trackers);
    return trackers;
}

export async function search(tracker, query) {
    const params = { tracker, query };
    const mediaItems = await requestApi({ method: 'GET', url: SEARCH_MEDIA_ITEMS, getParams: params });

    return mediaItems;
}
