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
    <div v-else class="container">
      <div class="row">
        <div v-for="mediaItem in searchResults" :key="mediaItem.id" class="col-xs-12 col-sm-6 col-md-6 col-lg-4 mb-3">
          <div class="card ribbon ribbon-clip ribbon-left">
            <div v-if="mediaItem.series_count" class="ribbon-target" style="top: 12px;">
              <span class="ribbon-inner bg-danger"></span>{{ mediaItem.series_count }}
            </div>
            <router-link :to="`/${tracker.id}/${mediaItem.id}`">
              <img :src="mediaItem.poster" class="card-img-top" style="width: 100%;">
            </router-link>
            <div class="card-body" style="padding: 1.5rem;">
              <h5 class="card-title text-center mb-1">{{ mediaItem.title }}</h5>
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
      };
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
      async handleSearch(searchQuery) {
        if (searchQuery === '') {
          return;
        }

        this.isLoading = true;

        let mediaItems;
        if (this.tracker.is_blocked) {
          mediaItems = await searchBlocked(this.tracker.id, searchQuery);
        } else {
          mediaItems = await search(this.tracker.id, searchQuery);
        }

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