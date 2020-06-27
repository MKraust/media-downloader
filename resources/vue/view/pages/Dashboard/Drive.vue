<template>
  <div>
    <div class="d-none d-md-block">
      <div class="d-flex align-items-center py-1 px-3 rounded bg-light">
        <span class="svg-icon svg-icon-xl mr-3 drive-icon" :class="{ [`svg-icon-${variant}`]: true }">
          <inline-svg src="/media/svg/icons/Devices/Hard-drive.svg" />
        </span>

        <span class="font-weight-bold text-dark-75 font-size-lg mb-0 mr-3 flex-grow-1">{{ drive.name }}</span>
        <span class="font-weight-bold text-dark-75 font-size-lg mb-0 mr-5">{{ drive.available }} / {{ drive.total }}</span>

        <b-progress height="1.6rem" class="d-flex" style="width: 200px;">
          <b-progress-bar :value="drive.usage_percent" :variant="variant" />
        </b-progress>
      </div>
    </div>

    <div class="d-md-none">
      <div class="px-4 pb-3 pt-1 rounded bg-light">
        <div class="d-flex align-items-center mb-2">
          <span class="svg-icon svg-icon-xl mr-3 drive-icon" :class="{ [`svg-icon-${variant}`]: true }">
            <inline-svg src="/media/svg/icons/Devices/Hard-drive.svg" />
          </span>
          <span class="font-weight-bold text-dark-75 font-size-lg mb-0 mr-3 flex-grow-1">{{ drive.name }}</span>
          <span class="font-weight-bold text-dark-75 font-size-lg mb-0">{{ drive.available }} / {{ drive.total }}</span>
        </div>

        <b-progress height="1.6rem" class="w-100">
          <b-progress-bar :value="drive.usage_percent" :variant="variant" />
        </b-progress>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Drive",
    props: ['drive'],
    computed: {
      variant() {
        if (this.drive.usage_percent >= 85) {
          return 'danger';
        }

        if (this.drive.usage_percent >= 50) {
          return 'warning';
        }

        if (this.drive.usage_percent < 50) {
          return 'success';
        }
      }
    }
  }
</script>

<style scoped>
  .drive-icon {
    padding-bottom: 0.4rem;
  }
</style>