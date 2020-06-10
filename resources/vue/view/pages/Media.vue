<template>
  <div>

    <div v-if="isLoading || media === null" class="d-flex justify-content-center" style="height: 400px;">
      <div v-if="isLoading" class="spinner spinner-track spinner-primary spinner-lg" style="margin-left: -1rem;"></div>
      <p v-else-if="media === null" class="text-center">Медиа контент не найден</p>
    </div>

    <div v-else>
      <MediaSubheader :title="media.title" />
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-4">
            <img :src="media.poster" class="img-thumbnail">
          </div>
          <div class="col-md-8">
            <div class="input-group mb-4">
              <b-form-select
                v-model="sortBy"
                :options="[
                  { value: 'size_int', text: 'По размеру'},
                  isSomeSeriesTorrents && { value: 'season', text: 'По сезону'},
                ]"
              />
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" @click="switchSortingOrder">
                  <i class="fa" :class="{ [sortingOrderIcon]: true }"></i>
                </button>
              </div>
            </div>
            <div v-for="torrent in sortedTorrents" class="mb-3">
              <Torrent :torrent="torrent" @download="handleDownload(torrent)" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import MediaSubheader from "./MediaSubheader";
  import Torrent from './Torrent';
  import { loadMedia, startDownload } from "@/api";

  export default {
    name: "Media",
    components: {
      MediaSubheader,
      Torrent,
    },
    data() {
      return {
        isLoading: false,
        media: null,
        sortBy: 'size_int',
        sortingOrder: 'desc'
      }
    },
    computed: {
      isSomeSeriesTorrents() {
        return this.media.torrents.some(torrent => torrent.content_type === 'series');
      },
      sortingOrderIcon() {
        return this.sortingOrder === 'asc' ? 'fa-sort-amount-down-alt' : 'fa-sort-amount-down';
      },
      sortedTorrents() {
        if (this.sortBy === 'size_int') {
          return this.$lodash.orderBy(this.media.torrents, ['size_int'], [this.sortingOrder]);
        }

        if (this.sortBy === 'season') {
          return this.$lodash.orderBy(this.media.torrents, ['season', 0], [this.sortingOrder]);
        }

        return this.medis.torrents;
      }
    },
    async created() {
      await this.init();
    },
    methods: {
      async init() {
        this.isLoading = true;
        const [ media ] = await Promise.all([loadMedia(this.$route.params.trackerId, this.$route.params.mediaId)]);
        console.log(media);
        this.media = media;
        this.isLoading = false;
      },
      handleDownload(torrent) {
        if (confirm(`Скачать "${torrent.name}"?`)) {
          startDownload(this.$route.params.trackerId, torrent.url, torrent.content_type);
        }
      },
      switchSortingOrder() {
        this.sortingOrder = this.sortingOrder === 'asc' ? 'desc' : 'asc';
      },
    }
  }
</script>

<style scoped>

</style>