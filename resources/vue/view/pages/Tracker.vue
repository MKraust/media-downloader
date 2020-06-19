<template>

  <div v-if="!tracker" class="d-flex justify-content-center" style="height: 400px;">
    <div class="spinner spinner-track spinner-primary spinner-lg" style="margin-left: -1rem;"></div>
  </div>
  <div v-else>
    <TrackerSubheader
      :title="tracker.title"
      :disable-search="isLoading"
      @search="handleSearch"
    />
    <div v-if="isLoading" class="d-flex justify-content-center" style="height: 400px;">
      <div class="spinner spinner-track spinner-primary spinner-lg" style="margin-left: -1rem;"></div>
    </div>
    <div v-else-if="error" class="container">
      <div class="card bg-white p-4">
        <h5 class="text-center py-3">Произошла ошибка. Возможно, трекер заблокирован.</h5>
        <div class="d-flex justify-content-center">
          <a href="App-prefs://prefs:root=General&path=VPN" class="btn btn-sm btn-primary d-inline">Включить VPN</a>
        </div>
      </div>
    </div>
    <div v-else class="container">
      <div class="row">
        <div v-for="mediaItem in searchResults" :key="mediaItem.id" class="col-xs-12 col-sm-6 col-md-6 col-lg-4  mb-3">
          <div class="card ribbon ribbon-clip ribbon-left">
            <div v-if="mediaItem.series_count" class="ribbon-target" style="top: 12px;">
              <span class="ribbon-inner bg-danger"></span>{{ mediaItem.series_count }}
            </div>
            <router-link :to="`/${tracker.id}/${mediaItem.id}`">
              <img :src="mediaItem.poster" class="card-img-top" style="width: 100%;">
            </router-link>
            <div class="card-body" style="padding: 1.5rem;">
              <h5 class="card-title text-center mb-1">
                <router-link :to="`/${tracker.id}/${mediaItem.id}`" class="hoverable">{{ mediaItem.title }}</router-link>
              </h5>
              <h5 v-if="mediaItem.original_title" class="text-center text-muted mb-0">{{ mediaItem.original_title }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  import asideToggleMixin from '@/mixins/asideToggleMixin';
  import TrackerSubheader from "./TrackerSubheader";
  import {
    search,
    searchBlocked,
  } from "@/api";

  export default {
    name: "Tracker",
    mixins: [asideToggleMixin],
    components: {
      TrackerSubheader,
    },
    data() {
      return {
        isLoading: false,
        isAsideHidden: false,
        error: false,
        isEndOfResultsReached: false,
        isLazyLoading: false,
        lastSearchQuery: '',
      };
    },
    created: function () {
      window.addEventListener('scroll', this.handleScroll)
    },
    destroyed: function () {
      window.removeEventListener('scroll', this.handleScroll)
    },
    computed: {
      searchResults() {
        return this.$store.getters.getSearchResults(this.tracker.id);
      },
      tracker() {
        let trackerId = this.$route.params.trackerId;
        return this.$store.getters.trackers.find(tracker => tracker.id === trackerId);
      },
    },
    methods: {
      async handleScroll() {
        let scrollPos = document.documentElement.scrollHeight - window.innerHeight - document.documentElement.scrollTop;
        if (scrollPos !== 0 || this.isEndOfResultsReached || this.isLazyLoading) {
          return;
        }

        this.isLazyLoading = true;

        let mediaItems;
        if (this.tracker.is_blocked) {
          mediaItems = await searchBlocked(this.tracker.id, this.lastSearchQuery, this.searchResults.length);
        } else {
          console.log(this.tracker.id, this.searchQuery, this.searchResults.length);
          mediaItems = await search(this.tracker.id, this.lastSearchQuery, this.searchResults.length);
        }

        if (mediaItems.length === 0) {
          this.isEndOfResultsReached = true;
          return;
        }

        const result = [...this.searchResults, ...mediaItems];
        this.$store.commit('saveSearch', {
          trackerId: this.tracker.id,
          media: result,
        });

        this.isLazyLoading = false;
      },
      async handleSearch(searchQuery) {
        if (searchQuery === '') {
          return;
        }

        this.isEndOfResultsReached = false;
        this.isLoading = true;
        this.error = false;

        let mediaItems;
        if (this.tracker.is_blocked) {
          mediaItems = await searchBlocked(this.tracker.id, searchQuery, this.searchResults.length);
          if (!mediaItems) {
            this.error = true;
            this.isLoading = false;
          }
        } else {
          mediaItems = await search(this.tracker.id, searchQuery, this.searchResults.length);
        }

        this.lastSearchQuery = searchQuery;

        this.$store.commit('saveSearch', {
          trackerId: this.tracker.id,
          media: mediaItems,
        });

        this.isLoading = false;
      },
    }
  }
</script>

<style scoped>

</style>