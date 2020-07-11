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
      <transition-group name="list" tag="div" class="row">
        <div v-for="mediaItem in searchResults" :key="mediaItem.id" class="col-xs-12 col-sm-6 col-md-6 col-lg-4 mb-7 d-flex">
          <MediaCard :media="mediaItem" class="align-self-stretch w-100" />
        </div>
      </transition-group>
    </div>
  </div>

</template>

<script>
  import asideToggleMixin from '@/mixins/asideToggleMixin';
  import TrackerSubheader from "./Subheader";
  import MediaCard from '@/view/pages/components/MediaCard';
  import { search } from "@/api";

  export default {
    mixins: [asideToggleMixin],
    components: {
      TrackerSubheader,
      MediaCard,
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
        if (scrollPos !== 0 || this.isEndOfResultsReached || this.isLazyLoading || !this.lastSearchQuery) {
          return;
        }

        this.isLazyLoading = true;

        let mediaItems = await search(this.tracker.id, this.lastSearchQuery, this.searchResults.length);

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

        let mediaItems = await search(this.tracker.id, searchQuery);

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
  .list-item {
    display: inline-block;
    margin-right: 10px;
  }
  .list-enter-active, .list-leave-active {
    transition: all 0.5s;
  }
  .list-enter, .list-leave-to {
    opacity: 0;
    transform: translateY(30px);
  }
</style>