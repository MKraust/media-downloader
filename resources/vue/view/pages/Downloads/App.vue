<template>
  <div>
    <Subheader title="Загрузки" />
    <div class="container py-4">
      <div v-for="download in downloads" :key="download.hash" class="mb-3">
        <Download :download="download" />
      </div>
    </div>
  </div>
</template>

<script>
  import asideToggleMixin from '@/mixins/asideToggleMixin';
  import Subheader from './Subheader';
  import Download from './Download';

  import { loadDownloads } from '@/api'

  export default {
    mixins: [asideToggleMixin],
    components: {
      Subheader,
      Download,
    },
    data() {
      return {
        isAsideHidden: true,
        isWatchingDownloads: false,
        downloads: [],
      };
    },
    created() {
      this.startWatchingDownloads();
    },
    destroyed() {
      this.stopWatchingDownloads();
    },
    methods: {
      async refreshDownloads() {
        this.downloads = await loadDownloads();
      },
      startWatchingDownloads() {
        this.isWatchingDownloads = true;
        this.runAsyncInterval(async () => {
          await this.refreshDownloads();
        }, 1000);
      },
      stopWatchingDownloads() {
        if (this.isWatchingDownloads) {
          this.isWatchingDownloads = false;
        }
      },
      async runAsyncInterval (cb, interval) {
        await cb();
        if (this.isWatchingDownloads) {
          setTimeout(() => this.runAsyncInterval(cb, interval), interval);
        }
      },
    }
  }
</script>