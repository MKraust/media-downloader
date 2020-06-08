import Vue from 'vue'
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        search: [],
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
        }
    },
    getters: {
        getSearchResults: state => trackerId => {
            let search = state.search.find(search => search.trackerId === trackerId);
            return search ? search.media : [];
        }
    }
});
