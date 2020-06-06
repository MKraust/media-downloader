<template>
  <div>
      <div class="py-2">
          <input
              v-model="searchQuery"
              class="form-control"
              type="text"
              placeholder="Поиск"
              @keyup.enter="$event.target.blur()"
              @blur="search">
      </div>

      <div class="py-2">
          <div class="row justify-content-center">
              <div v-for="mediaItem in mediaItems" :key="mediaItem.id" class="col-xs-12 col-sm-6 col-lg-4 mb-3">
                  <div class="card">
                      <router-link :to="'/media/' + mediaItem.id">
                          <img :src="mediaItem.poster" class="card-img-top" style="width: 100%;">
                      </router-link>
                      <div class="card-body">
                          <h5 class="card-title text-center">{{ mediaItem.title }}</h5>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import { search } from '../../api'

export default {
    name: "Tracker",
    data() {
        return {
            trackerId: this.$route.params.trackerId,
            searchQuery: '',
            mediaItems: [],
        }
    },
    watch: {
      trackerId(val) {
          this.$router.push('/tracker/' + val);
          this.searchQuery = '';
          this.mediaItems = [];
      }
    },
    methods: {
        async search() {
            if (this.searchQuery === '') {
                return;
            }

            const [ mediaItems ] = await Promise.all([
                search(this.trackerId, this.searchQuery)
            ]);

            this.mediaItems = mediaItems;
        }
    }
}
</script>

<style scoped>

</style>
