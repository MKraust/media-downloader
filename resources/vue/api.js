import axios from 'axios';

const BACKEND_API = `${window.location.protocol}//${window.location.host}/api`;
const LOAD_TRACKERS = BACKEND_API + '/trackers';
const LOAD_STORAGE_INFO = BACKEND_API + '/info/storage';
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

export async function search(tracker_id, search_query, offset = 0) {
    console.log(search_query);
    const params = { tracker_id, search_query, offset };
    const response = await axios.get(SEARCH_MEDIA_ITEMS, { params });

    return response.data;
}

export async function loadMedia(id) {
    const params = { id };
    const response = await axios.get(LOAD_MEDIA, { params });

    return response.data;
}

export async function startDownload(tracker, torrent) {
    const params = { id: torrent.id };
    await axios.get(START_DOWNLOAD, { params });
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

export async function loadStorageInfo() {
    return (await axios.get(LOAD_STORAGE_INFO)).data;
}