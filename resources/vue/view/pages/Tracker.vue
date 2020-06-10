<template>
  <div class="d-flex flex-column vh-100">
    <TrackerSubheader
      :title="tracker.title"
      @search="handleSearch"
      disable-search="isLoading"
    />
    <div v-if="isLoading" class="d-flex justify-content-center" style="flex: 1;">
      <div class="spinner spinner-track spinner-primary spinner-lg"></div>
    </div>
    <div v-else class="container">
      <div class="row">
        <div v-for="mediaItem in searchResults" :key="mediaItem.id" class="col-xs-12 col-sm-6 col-md-6 col-xl-4 mb-3">
          <div class="card">
            <router-link :to="`/${tracker.id}/${mediaItem.id}`">
              <img :src="mediaItem.poster" class="card-img-top" style="width: 100%;">
            </router-link>
            <div class="card-body">
              <h5 class="card-title text-center">{{ mediaItem.title }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  import TrackerSubheader from "./TrackerSubheader";
  import { search } from "@/api";

  export default {
    name: "Tracker",
    components: {
      TrackerSubheader,
    },
    data() {
      return {
        isLoading: false,
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

        const [ mediaItems ] = await Promise.all([
          search(this.tracker.id, searchQuery)
        ]);

        this.$store.commit('saveSearch', {
          trackerId: this.tracker.id,
          media: mediaItems,
        });

        this.isLoading = false;
      }
    }
  }
</script>

<style scoped>

</style>