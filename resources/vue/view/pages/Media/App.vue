<template>
  <div>
    <div v-if="error">
      <Subheader title="Ошибка" />
      <div class="container py-4">
        <div class="alert alert-custom alert-outline alert-outline-danger alert-shadow bg-white" role="alert">
          <div class="alert-icon"><i class="flaticon-warning"></i></div>
          <div class="alert-text h5 mb-0">
            Произошла ошибка. Подробности в
            <strong>
              <a href="https://sentry.io/organizations/personal-purposes/issues/?project=5284009" class="text-danger text-hover-danger">Sentry</a>
            </strong>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="isLoading || media === null" class="d-flex justify-content-center" style="height: 400px;">
      <div v-if="isLoading" class="spinner spinner-track spinner-primary spinner-lg" style="margin-left: -1rem;"></div>
      <p v-else-if="media === null" class="text-center">Медиа контент не найден</p>
    </div>

    <div v-else>
      <Subheader :title="media.title" :subtitle="media.original_title" />
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="card ribbon ribbon-clip ribbon-left mb-4">
              <div v-if="media.series_count" class="ribbon-target" style="top: 12px;">
                <span class="ribbon-inner bg-danger"></span>{{ media.series_count }}
              </div>
              <img :src="media.poster" class="card-img-top card-img-bottom" style="width: 100%;">
            </div>
            <button
              class="btn btn-lg btn-shadow w-100 font-weight-bolder"
              :class="{
                [`btn-${favoriteButtonType}`]: true,
                spinner: isTogglingFavorite,
                'spinner-white': isTogglingFavorite,
                'spinner-right': isTogglingFavorite,
              }"
              :disabled="isTogglingFavorite"
              @click="toggleFavorite"
            >
              {{ favoriteButtonText }}
            </button>
          </div>
          <div class="col-md-8">
            <div class="input-group mb-4">
              <b-form-select
                v-model="sortBy"
                :options="isSomeSeriesTorrents
                  ? [{ value: 'season', text: 'По сезону'}, { value: 'size_int', text: 'По размеру'}]
                  : [{ value: 'size_int', text: 'По размеру'}]
                "
              />
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" @click="switchSortingOrder">
                  <i class="fa" :class="{ [sortingOrderIcon]: true }"></i>
                </button>
              </div>
            </div>
            <div v-for="torrent in sortedTorrents" class="mb-3">
              <Torrent :torrent="torrent" @download="handleDownload(torrent)" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Subheader from "./Subheader";
  import Torrent from './Torrent';
  import { addToFavorites, loadMedia, removeFromFavorites, startDownload } from '@/api';
  import notifyMixin from '@/mixins/notifyMixin';
  import asideToggleMixin from '@/mixins/asideToggleMixin';
  import { confirm } from '@/alert';

  export default {
    mixins: [notifyMixin, asideToggleMixin],
    components: {
      Subheader,
      Torrent,
    },
    data() {
      return {
        isLoading: false,
        error: false,
        media: null,
        sortBy: 'size_int',
        sortingOrder: 'desc',
        isAsideHidden: false,
        isTogglingFavorite: false,
      }
    },
    computed: {
      tracker() {
        let trackerId = this.$route.params.trackerId;
        return this.$store.getters.trackers.find(tracker => tracker.id === trackerId);
      },
      isSomeSeriesTorrents() {
        return this.media.torrents.some(torrent => torrent.content_type === 'series');
      },
      sortingOrderIcon() {
        return this.sortingOrder === 'asc' ? 'fa-sort-amount-down-alt' : 'fa-sort-amount-down';
      },
      sortedTorrents() {
        if (this.sortBy === 'size_int') {
          return this.$lodash.orderBy(this.media.torrents, ['size_int'], [this.sortingOrder]);
        }

        if (this.sortBy === 'season') {
          return this.$lodash.orderBy(this.media.torrents, ['season', 0], [this.sortingOrder]);
        }

        return this.medis.torrents;
      },
      favoriteButtonType() {
        return this.media.is_favorite ? 'danger' : 'info';
      },
      favoriteButtonText() {
        return this.media.is_favorite ? 'Удалить из избранного' : 'В избранное';
      }
    },
    watch: {
      tracker() {
        this.init();
      },
    },
    async created() {
      if (this.tracker) {
        await this.init();
      }
    },
    methods: {
      async toggleFavorite() {
        this.isTogglingFavorite = true;

        if (this.media.is_favorite) {
          await removeFromFavorites(this.media.id);
          this.media.is_favorite = false;
        } else {
          await addToFavorites(this.media.id);
          this.media.is_favorite = true;
        }

        this.isTogglingFavorite = false;
      },
      async init() {
        this.isLoading = true;

        try {
          this.media = await loadMedia(this.$route.params.mediaId);
        } catch (e) {
          this.error = true;
        }

        this.isLoading = false;
      },
      handleDownload(torrent) {
        confirm('Скачать?', torrent.name, () => {
          startDownload(this.tracker, torrent);
          this.notifySuccess('Загрузка началась', torrent.name);
        });
      },
      switchSortingOrder() {
        this.sortingOrder = this.sortingOrder === 'asc' ? 'desc' : 'asc';
      },
    }
  }
</script>

<style scoped>

</style>