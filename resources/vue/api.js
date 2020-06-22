import axios from 'axios';

const BACKEND_API = `${window.location.protocol}//${window.location.host}/api`;
const LOAD_TRACKERS = BACKEND_API + '/trackers';
const START_DOWNLOAD = BACKEND_API + '/download';
const START_DOWNLOAD_FROM_FILE = START_DOWNLOAD + '/file';

const SEARCH_MEDIA_ITEMS = BACKEND_API + '/search';
const GET_SEARCH_URL = SEARCH_MEDIA_ITEMS + '/url';
const PARSE_SEARCH_RESULTS_HTML = SEARCH_MEDIA_ITEMS + '/parse';

const LOAD_MEDIA = BACKEND_API + '/media';
const GET_MEDIA_URLS = LOAD_MEDIA + '/urls';
const PARSE_MEDIA = LOAD_MEDIA + '/parse';

const LOAD_FAVORITES = BACKEND_API + '/favorites/list';
const ADD_TO_FAVORITES = BACKEND_API + '/favorites/add';
const REMOVE_FROM_FAVORITES = BACKEND_API + '/favorites/remove';

const LOAD_DOWNLOADS = BACKEND_API + '/download/list';
const RESUME_DOWNLOAD = BACKEND_API + '/download/resume';
const PAUSE_DOWNLOAD = BACKEND_API + '/download/pause';
const DELETE_DOWNLOAD = BACKEND_API + '/download/delete';

export async function loadTrackers() {
    const response = await axios.get(LOAD_TRACKERS);

    return response.data;
}

export async function search(tracker_id, query, offset = 0) {
    console.log(query);
    const params = { tracker_id, query, offset };
    const response = await axios.get(SEARCH_MEDIA_ITEMS, { params });

    return response.data;
}

export async function loadMedia(id) {
    const params = { id };
    const response = await axios.get(LOAD_MEDIA, { params });

    return response.data;
}

export async function startDownload(tracker, torrent) {
    if (!tracker.is_blocked) {
        const params = { id: torrent.id };
        await axios.get(START_DOWNLOAD, { params });
        return;
    }

    const response = await axios.get(torrent.url, { responseType: 'blob' });
    const file = response.data;
    console.log(file);

    let body = new FormData();
    body.append('id', torrent.id);
    body.append('file', file);
    body.append('tracker_id', tracker.id);

    await axios.post(START_DOWNLOAD_FROM_FILE, body, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });
}

export async function searchBlocked(tracker_id, query, offset = 0) {
    try {
        const searchUrl = (await axios.get(GET_SEARCH_URL, { params: { tracker_id, query, offset } })).data;
        const html = (await axios.get(searchUrl)).data;
        const mediaItems = (await axios.post(PARSE_SEARCH_RESULTS_HTML, { tracker_id, html })).data;

        return mediaItems;
    } catch (error) {
        return false;
    }
}

export async function loadMediaBlocked(tracker_id, id) {
    const mediaUrls = (await axios.get(GET_MEDIA_URLS, { params: { tracker_id, id } })).data;
    const [ mediaHtml, torrentsHtml ] = await Promise.all([
      axios.get(mediaUrls.media),
      axios.get(mediaUrls.torrents),
    ]);
    const media = (await axios.post(PARSE_MEDIA, { tracker_id, id, html: { media: mediaHtml.data, torrents: torrentsHtml.data } })).data;

    return media;
}

export async function loadDownloads() {
    const response = await axios.get(LOAD_DOWNLOADS);

    return response.data;
}

export async function resumeDownload(hash) {
    await axios.post(RESUME_DOWNLOAD, { hash });
}

export async function pauseDownload(hash) {
    await axios.post(PAUSE_DOWNLOAD, { hash });
}

export async function deleteDownload(hash) {
    await axios.post(DELETE_DOWNLOAD, { hash });
}

export async function loadFavorites() {
    return (await axios.get(LOAD_FAVORITES)).data;
}

export async function addToFavorites(id) {
    await axios.post(ADD_TO_FAVORITES, { id });
}

export async function removeFromFavorites(id) {
    await axios.post(REMOVE_FROM_FAVORITES, { id });
}