<template>
  <div
    class="subheader py-3 py-lg-8 subheader-transparent"
    v-bind:class="subheaderClasses"
    id="kt_subheader"
  >
    <div
      class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap"
      v-bind:class="{ 'container-fluid': widthFluid, container: !widthFluid }"
    >
      <div class="d-flex align-items-center mw-100 mr-5">
        <div>
          <a class="btn btn-sm btn-icon btn-primary btn-shadow mr-4" @click="$router.back()">
            <span class="svg-icon svg-icon-xl">
              <inline-svg src="/media/svg/icons/Navigation/Angle-left.svg" />
            </span>
          </a>
        </div>
        <!--begin::Page Title-->
        <div class="div">
          <h2 class="subheader-title text-dark font-weight-bold mr-3 mb-0 text-truncate">{{ title }}</h2>
          <ul v-if="subtitle" class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-0 p-0">
            <li class="breadcrumb-item">
              <span class="text-muted">{{ subtitle }}</span>
            </li>
          </ul>
        </div>
        <!--end::Page Title-->
      </div>
    </div>
  </div>
</template>

<script>
  import subheaderMixin from '@/view/layout/subheader/subheaderMixin';

  export default {
    name: "Subheader",
    mixins: [subheaderMixin],
    computed: {
      tracker() {
        let trackerId = this.$route.params.trackerId;
        return this.$store.getters.trackers.find(tracker => tracker.id === trackerId);
      },
    },
  }
</script>

<style scoped>

</style>