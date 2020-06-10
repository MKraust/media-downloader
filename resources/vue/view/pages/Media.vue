<template>
  <div>
    <p v-if="isLoading" class="text-center">Загрузка</p>
    <p v-else-if="media === null" class="text-center">Медиа контент не найден</p>
    <div v-else>
      <MediaSubheader :title="media.title" />
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <img :src="media.poster" class="img-thumbnail">
          </div>
          <div class="col-md-8">
            <div v-for="torrent in media.torrents" class="mb-3">
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
      }
    }
  }
</script>

<style scoped>

</style>