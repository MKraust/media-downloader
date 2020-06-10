<template>
  <div>
    <TrackerSubheader :title="tracker.title" @search="handleSearch" />
    <div class="container">
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
  import { mapGetters } from "vuex";
  import TrackerSubheader from "./TrackerSubheader";
  import { search } from "@/api";

  export default {
    name: "Tracker",
    components: {
      TrackerSubheader,
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

        const [ mediaItems ] = await Promise.all([
          search(this.tracker.id, searchQuery)
        ]);

        this.$store.commit('saveSearch', {
          trackerId: this.tracker.id,
          media: mediaItems,
        });
      }
    }
  }
</script>

<style scoped>

</style>