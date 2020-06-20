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

const TORRENT_BASE_URL = 'http://torrent.mkraust.ru';
const LOAD_DOWNLOADS = TORRENT_BASE_URL + '/query/torrents';
const RESUME_DOWNLOAD = TORRENT_BASE_URL + '/command/resume';
const PAUSE_DOWNLOAD = TORRENT_BASE_URL + '/command/pause';
const DELETE_DOWNLOAD = TORRENT_BASE_URL + '/command/deletePerm';

export async function loadTrackers() {
    const response = await axios.get(LOAD_TRACKERS);

    return response.data;
}

export async function search(tracker, query, offset = 0) {
    console.log(query);
    const params = { tracker, query, offset };
    const response = await axios.get(SEARCH_MEDIA_ITEMS, { params });

    return response.data;
}

export async function loadMedia(tracker, id) {
    const params = { tracker, id };
    const response = await axios.get(LOAD_MEDIA, { params });

    return response.data;
}

export async function startDownload(tracker, url, type) {
    if (!tracker.is_blocked) {
        const params = { tracker: tracker.id, url, type };
        await axios.get(START_DOWNLOAD, { params });
        return;
    }

    const response = await axios.get(url, { responseType: 'blob' });
    const file = response.data;
    console.log(file);

    let body = new FormData();
    body.set('tracker', tracker.id);
    body.set('type', type);
    body.append('file', file);

    await axios.post(START_DOWNLOAD_FROM_FILE, body, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });
}

export async function searchBlocked(tracker, query, offset = 0) {
    try {
        const searchUrl = (await axios.get(GET_SEARCH_URL, { params: { tracker, query, offset } })).data;
        const html = (await axios.get(searchUrl)).data;
        const mediaItems = (await axios.post(PARSE_SEARCH_RESULTS_HTML, { tracker, html })).data;

        return mediaItems;
    } catch (error) {
        return false;
    }
}

export async function loadMediaBlocked(tracker, id) {
    const mediaUrls = (await axios.get(GET_MEDIA_URLS, { params: { tracker, id } })).data;
    const [ mediaHtml, torrentsHtml ] = await Promise.all([
      axios.get(mediaUrls.media),
      axios.get(mediaUrls.torrents),
    ]);
    const media = (await axios.post(PARSE_MEDIA, { tracker, id, html: { media: mediaHtml.data, torrents: torrentsHtml.data } })).data;

    return media;
}

export async function loadDownloads() {
    const response = await axios.get(LOAD_DOWNLOADS);

    return response.data;
}

export async function resumeDownload(hash) {
    await axios.post(RESUME_DOWNLOAD, `hash=${hash}`, {
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });
}

export async function pauseDownload(hash) {
    await axios.post(PAUSE_DOWNLOAD, `hash=${hash}`, {
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });
}

export async function deleteDownload(hash) {
    await axios.post(DELETE_DOWNLOAD, `hashes=${hash}`, {
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });
}