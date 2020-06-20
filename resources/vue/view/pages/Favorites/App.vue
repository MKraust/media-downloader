<template>
  <div>
    <BasicSubheader title="Избранное" />
    <div class="container py-4">
      <div v-if="favorites.length > 0" class="row">
        <div v-for="favorite in favorites" :key="favorite.id" class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-3">
          <MediaCard :media="favorite" />
        </div>
      </div>
      <div v-else class="card bg-white p-4">
        <h5 class="text-center py-3 mb-0">В избранном пока ничего нет</h5>
      </div>
    </div>
  </div>
</template>

<script>
  import asideToggleMixin from '@/mixins/asideToggleMixin';
  import BasicSubheader from '../components/BasicSubheader';
  import MediaCard from '../components/MediaCard';
  import { loadFavorites } from '@/api';

  export default {
    name: "App",
    mixins: [asideToggleMixin],
    components: {
      BasicSubheader,
      MediaCard,
    },
    data() {
      return {
        isAsideHidden: true,
        favorites: [],
      };
    },
    created() {
      this.reloadData();
    },
    methods: {
      async reloadData() {
        this.favorites = await loadFavorites();
      }
    }
  }
</script>

<style scoped>

</style>