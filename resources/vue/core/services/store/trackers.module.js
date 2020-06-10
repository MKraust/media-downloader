export default {
  state: {
    search: [],
    trackers: [],
  },
  mutations: {
    saveSearch(state, payload) {
      let search = state.search.find(search => search.trackerId === payload.trackerId);
      if (search) {
        search.media = payload.media;
        return;
      }

      state.search.push({
        trackerId: payload.trackerId,
        media: payload.media,
      });
    },
    saveTrackers(state, trackers) {
      state.trackers = trackers;
    }
  },
  getters: {
    getSearchResults: state => trackerId => {
      let search = state.search.find(search => search.trackerId === trackerId);
      return search ? search.media : [];
    },
    trackers: state => {
      return state.trackers;
    }
  }
};
