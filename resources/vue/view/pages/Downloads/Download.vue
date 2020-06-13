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

      <h5 class="flex-grow-1 mr-3 mb-0 text-truncate">{{ download.name }}</h5>

      <div class="d-flex align-items-center mr-5" style="min-width: 100px; max-width: 100px;">
        <i class="fas fa-tachometer-alt mr-2"></i>
        <span>{{ speed }}</span>
      </div>

      <div class="d-flex align-items-center mr-3" style="min-width: 100px; max-width: 100px;">
        <i class="fas fa-clock mr-2"></i>
        <span>{{ estimate }}</span>
      </div>

      <div style="min-width: 250px; max-width: 250px;">
        <b-progress height="2rem" class="d-flex">
          <b-progress-bar :value="progress" :variant="statusColor" :animated="isInProgress" />
        </b-progress>
      </div>

    </div>

    <div class="d-block d-xl-none">

      <h5 class="text-truncate mb-2">{{ download.name }}</h5>

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
        <b-progress height="2rem" class="d-flex flex-grow-1">
          <b-progress-bar :value="50" :variant="statusColor" :animated="isInProgress" />
        </b-progress>
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
        return ['downloading', 'uploading', 'metaDL', 'checkingDL', 'checkingUP'].includes(this.download.state);
      },
      statusColor() {
        switch (this.download.state) {
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
          switch (this.download.state) {
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

          this.download.state = 'pausedDL';
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
        return humanizeBytes(this.download.dlspeed, 1) + '/c';
      },
      estimate() {
        const eta = this.download.eta;
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
        return `${days} дн`;
      }
    }
  }
</script>

<style scoped>

</style>