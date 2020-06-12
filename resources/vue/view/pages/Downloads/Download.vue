<template>
  <div class="card bg-white p-4">
    <div class="d-flex align-items-center">

      <div class="mr-3">
        <div class="switch d-flex align-items-center">
          <label class="mb-0">
            <input type="checkbox" checked="checked" name="select" />
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

      <div style="min-width: 300px; max-width: 300px;">
        <b-progress height="2rem" class="d-flex">
          <b-progress-bar :value="progress" />
        </b-progress>
      </div>

    </div>
  </div>
</template>

<script>
  import { humanizeBytes } from "@/helper";

  export default {
    name: "Download",
    props: ['download'],
    computed: {
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