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
      <div class="d-flex align-items-baseline mr-5">
        <!--begin::Page Title-->
        <h2 class="subheader-title text-dark font-weight-bold my-2 mr-3">
          {{ title }}
        </h2>
        <!--end::Page Title-->
      </div>
    </div>
  </div>
</template>

<script>
  import subheaderMixin from "@/view/layout/subheader/subheaderMixin";

  export default {
    name: "MediaSubheader",
    mixins: [subheaderMixin],
  }
</script>

<style scoped>

</style>