<template>
    <div class="py-3">
        <p v-if="isLoading" class="text-center">Загрузка</p>
        <p v-else-if="media === null" class="text-center">Медиа контент не найден</p>
        <div v-else>
            <h2 class="text-center mb-3">{{ media.title }}</h2>
            <div class="row">
                <div class="col-xs-12 col-lg-6 justify-content-center mb-3 mb-lg-0">
                    <img :src="media.poster" style="max-width: 100%;">
                </div>
                <div class="col-xs-12 col-lg-6">
                    <ul class="list-group">
                        <li v-for="torrent in media.torrents" class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">{{ torrent.name }}</h5>
                                <div>
                                    <a class="btn btn-sm btn-primary" href="#">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-2">
                                <span class="badge badge-primary"><i class="fas fa-video"></i> {{ torrent.quality }}</span>
                                <span class="badge badge-primary"><i class="fas fa-weight-hanging"></i> {{ torrent.size }}</span>
                            </div>
                            <div>Перевод: {{ torrent.voice_acting }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { loadMedia } from "../../api";

export default {
    name: "Media",
    async created() {
        await this.init();
    },
    data() {
        return {
            isLoading: false,
            media: null,
        }
    },
    methods: {
        async init() {
            this.isLoading = true;
            const [ media ] = await Promise.all([loadMedia(this.$route.params.mediaId)]);
            console.log(media);
            this.media = media;
            this.isLoading = false;
        }
    }
}
</script>

<style scoped>

</style>
