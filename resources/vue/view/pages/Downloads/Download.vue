<template>
  <div class="card bg-white p-4">
    <div class="d-none d-xl-flex align-items-center">

      <div class="mr-3">
        <div class="switch switch-outline switch-sm d-flex align-items-center" :class="{ [`switch-${statusColor}`]: true }">
          <label class="mb-0">
            <input v-model="isActive" :disabled="isChangingState" type="checkbox" />
            <span></span>
          </label>
        </div>
      </div>

      <div class="symbol symbol-30 symbol-light mr-3">
        <span class="symbol-label">
          <img :src="tracker.icon" class="h-75 w-75 alight-self-center">
        </span>
      </div>

      <h5 class="flex-grow-1 mr-3 mb-0 text-truncate">
        {{ media.title }}
        <span v-if="torrent.content_type !== 'movie'" class="ml-2 text-muted h6">
          {{ torrent.name }}
        </span>
      </h5>

      <div class="d-flex align-items-center mr-5" style="min-width: 100px; max-width: 100px;">
        <i class="fas fa-tachometer-alt mr-2"></i>
        <span>{{ speed }}</span>
      </div>

      <div class="d-flex align-items-center mr-3" style="min-width: 100px; max-width: 100px;">
        <i class="fas fa-clock mr-2"></i>
        <span>{{ estimate }}</span>
      </div>

      <div class="mr-3" style="min-width: 250px; max-width: 250px;">
        <b-progress height="2rem" class="d-flex">
          <b-progress-bar :value="progress" :variant="statusColor" :animated="isInProgress" />
        </b-progress>
      </div>

      <b-button variant="outline-danger" size="sm" class="btn-icon btn-delete" @click="$emit('delete')">
        <i class="flaticon2-rubbish-bin-delete-button"></i>
      </b-button>

    </div>

    <div class="d-block d-xl-none">

      <h5 class="text-truncate mb-1">{{ media.title }}</h5>
      <h6 v-if="!isMovie" class="text-truncate text-muted mb-3">{{ torrent.name }}</h6>

      <div class="d-flex mb-3">

        <div class="d-flex align-items-center mr-3" style="min-width: 100px; max-width: 100px;">
          <i class="fas fa-tachometer-alt mr-2"></i>
          <span>{{ speed }}</span>
        </div>

        <div class="d-flex align-items-center" style="min-width: 100px; max-width: 100px;">
          <i class="fas fa-clock mr-2"></i>
          <span>{{ estimate }}</span>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <div class="switch switch-outline switch-sm d-flex align-items-center mr-4" :class="{ [`switch-${statusColor}`]: true }">
          <label class="mb-0">
            <input v-model="isActive" :disabled="isChangingState" type="checkbox" />
            <span></span>
          </label>
        </div>
        <b-progress height="2rem" class="d-flex flex-grow-1 mr-4">
          <b-progress-bar :value="progress" :variant="statusColor" :animated="isInProgress" />
        </b-progress>

        <b-button variant="outline-danger" size="sm" class="btn-icon btn-delete" @click="$emit('delete')">
          <i class="flaticon2-rubbish-bin-delete-button"></i>
        </b-button>
      </div>

    </div>
  </div>
</template>

<script>
  import { humanizeBytes } from "@/helper";

  import { resumeDownload, pauseDownload } from '@/api';

  export default {
    name: "Download",
    props: ['download'],
    data() {
      return {
        isChangingState: false,
      };
    },
    computed: {
      isInProgress() {
        return ['downloading', 'uploading', 'metaDL', 'checkingDL', 'checkingUP'].includes(this.download.state_original);
      },
      statusColor() {
        switch (this.download.state_original) {
          case 'error':
            return 'danger';

          case 'stalledUP':
          case 'stalledDL':
            return 'warning';

          default:
            return 'primary';
        }
      },
      isActive: {
        get() {
          switch (this.download.state_original) {
            case 'error':
            case 'pausedUP':
            case 'pausedDL':
              return false;

            default:
              return true;
          }
        },
        async set(isActive) {
          this.isChangingState = true;

          this.download.state_original = 'pausedDL';
          if (isActive) {
            await resumeDownload(this.download.hash);
          } else {
            await pauseDownload(this.download.hash);
          }

          this.isChangingState = false;
        },
      },
      progress() {
        return this.download.progress * 100;
      },
      speed() {
        return humanizeBytes(this.download.download_speed_in_bytes_per_second, 1) + '/c';
      },
      estimate() {
        const eta = this.download.estimate_in_seconds;
        if (eta < 60) {
          return `${eta} сек`;
        }

        if (eta < 3600) {
          const minutes = Math.floor(eta / 60);
          return `${minutes} мин`;
        }

        if (eta < 3600 * 24) {
          const hours = Math.floor(eta / 3600);
          const minutes = Math.floor((eta % 3600) / 60);
          return `${hours} ч ${minutes} мин`;
        }

        const days = Math.floor(eta / 3600 * 24);
        if (days > 5) {
          return '∞';
        }

        return `${days} дн`;
      },
      media() {
        return this.download.media;
      },
      torrent() {
        return this.download.torrent;
      },
      tracker() {
        let trackerId = this.media.tracker_id;
        return this.$store.getters.trackers.find(tracker => tracker.id === trackerId);
      },
      isMovie() {
        return this.torrent.content_type === 'movie';
      },
    },
  }
</script>

<style lang="scss" scoped>
  .btn-delete {
    height: 2rem !important;
    width: 2rem !important;

    i {
      font-size: 1.1rem;
    }
  }
</style>