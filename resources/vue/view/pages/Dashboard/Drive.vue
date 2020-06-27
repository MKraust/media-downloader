<template>
  <div class="d-flex align-items-center">
    <span class="svg-icon svg-icon-xl svg-icon-primary mr-3 drive-icon">
      <inline-svg src="/media/svg/icons/Devices/Hard-drive.svg" />
    </span>

    <h5 class="font-weight-bold mb-0 mr-3 flex-grow-1">{{ drive.name }}</h5>

    <h6 class="font-weight-bold mb-0 mr-5">{{ drive.available }} / {{ drive.total }}</h6>

    <b-progress height="1.6rem" class="d-flex" style="width: 300px;">
      <b-progress-bar :value="drive.usage_percent" :variant="progressVariant" />
    </b-progress>
  </div>
</template>

<script>
  export default {
    name: "Drive",
    props: ['drive'],
    computed: {
      progressVariant() {
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