<template>
  <div class="card ribbon ribbon-clip ribbon-left">
    <div v-if="media.series_count" class="ribbon-target" style="top: 12px;">
      <span class="ribbon-inner bg-danger"></span>{{ media.series_count }}
    </div>
    <router-link :to="`/${media.tracker_id}/${media.id}`">
      <img :src="media.poster" class="card-img-top" style="width: 100%;">
    </router-link>
    <div class="card-body" style="padding: 1.5rem;">
      <h5 class="card-title text-center mb-1">
        <router-link :to="`/${media.tracker_id}/${media.id}`" class="hoverable">{{ media.title }}</router-link>
      </h5>
      <h5 v-if="media.original_title" class="text-center text-muted mb-0">{{ media.original_title }}</h5>
    </div>
  </div>
</template>

<script>
  export default {
    name: "MediaCard",
    props: ['media'],
  }
</script>

<style scoped>

</style>