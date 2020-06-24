<template>
  <div>
    <BasicSubheader title="Загрузки" />
    <div class="container py-4">
      <div v-if="downloads.length > 0">
        <div v-for="download in sortedDownloads" :key="download.hash" class="mb-3">
          <Download :download="download" @delete="handleDelete(download)" />
        </div>
      </div>
      <div v-else class="card bg-white p-4">
        <h5 class="text-center py-3 mb-0">Ничего не загружается</h5>
      </div>
    </div>
  </div>
</template>

<script>
  import asideToggleMixin from '@/mixins/asideToggleMixin';
  import BasicSubheader from '../components/BasicSubheader';
  import Download from './Download';
  import { confirm } from '@/alert';

  import { loadDownloads, deleteDownload } from '@/api'

  export default {
    mixins: [asideToggleMixin],
    components: {
      BasicSubheader,
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
    computed: {
      sortedDownloads() {
        return this.$lodash.orderBy(this.downloads, ['media.torrent_id', 'media.title', 'torrent.name'], ['asc']);
      },
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
      async handleDelete(downloadToDelete) {
        const torrent = downloadToDelete.torrent;
        const downloadName = downloadToDelete.media.title + (torrent.content_type !== 'movie' ? ` ${torrent.name}` : '');

        confirm('Остановить загрузку?', downloadName, async () => {
          await deleteDownload(downloadToDelete.hash);
          this.downloads = this.downloads.filter(download => download.hash !== downloadToDelete.hash);
        });
      }
    }
  }
</script>